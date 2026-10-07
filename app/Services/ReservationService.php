<?php

namespace App\Services;

use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Itinerary;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;

class ReservationService
{
    public function __construct(private readonly InvoiceService $invoices)
    {
    }

    public function assertItineraryBelongsToProduct(Product $product, Itinerary $itinerary): void
    {
        abort_unless($itinerary->product_id === $product->id, 404);
    }

    public function createReservation(array $validated, Product $product, Itinerary $itinerary): Booking
    {
        $this->assertItineraryBelongsToProduct($product, $itinerary);

        return DB::transaction(function () use ($validated, $itinerary) {
            $lockedItinerary = Itinerary::query()
                ->whereKey($itinerary->id)
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];

            if ($lockedItinerary->available_stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'No hay plazas suficientes para esta salida.',
                ]);
            }

            $client = $this->resolveClient($validated);
            $booking = $this->createBooking($client->id);
            $totalBookingPrice = $this->createPassengersAndCalculateBookingPrice(
                $validated,
                $lockedItinerary,
                $booking->id
            );

            $booking->update(['total_price' => $totalBookingPrice]);
            $this->syncBookingMetaFromReservationForm($booking, $validated['notes'] ?? null);

            $lockedItinerary->decrement('available_stock', $quantity);

            $booking->itineraries()->attach($lockedItinerary->id, [
                'itinerary_order' => 1,
            ]);

            session(['booking_id' => $booking->id]);

            return $booking->fresh(['passengers', 'client', 'itineraries']);
        });
    }

    public function bookingFromSession(Product $product, Itinerary $itinerary): Booking
    {
        $this->assertItineraryBelongsToProduct($product, $itinerary);

        $bookingId = session('booking_id');
        abort_unless($bookingId, 404);

        $booking = Booking::query()->findOrFail($bookingId);

        abort_unless(
            $booking->itineraries()->where('itineraries.id', $itinerary->id)->exists(),
            404
        );

        return $booking;
    }

    public function amountInCents(Booking $booking): int
    {
        return (int) bcmul((string) $booking->total_price, '100', 0);
    }

    public function getOrCreatePaymentIntent(Booking $booking, Product $product): PaymentIntent
    {
        $this->configureStripe();

        $existingPayment = $booking->payments()
            ->where('type_id', Type::idFor(Payment::class, Type::PAID_BY_STRIPE))
            ->whereNotNull('transaction_id')
            ->latest('id')
            ->first();

        if ($existingPayment) {
            $intent = PaymentIntent::retrieve($existingPayment->transaction_id);

            if (in_array($intent->status, ['requires_payment_method', 'requires_confirmation', 'requires_action', 'processing'], true)) {
                return $intent;
            }

            if ($intent->status === Status::PAYMENT_STRIPE_SUCCEEDED) {
                $this->confirmSuccessfulPayment($booking, $intent->id, (int) $intent->amount);

                return $intent;
            }
        }

        $intent = PaymentIntent::create([
            'amount' => $this->amountInCents($booking),
            'currency' => strtolower((string) $booking->currency),
            'metadata' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'booking_id' => $booking->id,
                'external_ref' => $booking->external_ref,
            ],
        ]);

        $booking->payments()->create([
            'amount' => $booking->total_price,
            'currency' => $booking->currency,
            'transaction_id' => $intent->id,
            'status_id' => Status::idFor(Payment::class, Status::PAYMENT_PENDING),
            'type_id' => Type::idFor(Payment::class, Type::PAID_BY_STRIPE),
        ]);

        return $intent;
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        $this->configureStripe();

        return PaymentIntent::retrieve($paymentIntentId);
    }

    public function confirmSuccessfulPayment(Booking $booking, string $paymentIntentId, int $amountCents): void
    {
        $alreadyPaid = $booking->payments()
            ->where('transaction_id', $paymentIntentId)
            ->where('status_id', Status::idFor(Payment::class, Status::PAYMENT_PAID))
            ->exists();

        if ($alreadyPaid && $booking->hasStatusSlug(Status::BOOKING_PAID)) {
            return;
        }

        DB::transaction(function () use ($booking, $paymentIntentId, $amountCents) {
            $locked = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            $locked->update(['status_id' => Status::idFor(Booking::class, Status::BOOKING_PAID)]);

            $amount = bcdiv((string) $amountCents, '100', 2);
            $payment = $locked->payments()->where('transaction_id', $paymentIntentId)->first();

            if ($payment) {
                $payment->update([
                    'amount' => $amount,
                    'status_id' => Status::idFor(Payment::class, Status::PAYMENT_PAID),
                    'type_id' => Type::idFor(Payment::class, Type::PAID_BY_STRIPE),
                ]);
            } else {
                $locked->payments()->create([
                    'amount' => $amount,
                    'currency' => $locked->currency,
                    'transaction_id' => $paymentIntentId,
                    'status_id' => Status::idFor(Payment::class, Status::PAYMENT_PAID),
                    'type_id' => Type::idFor(Payment::class, Type::PAID_BY_STRIPE),
                ]);
            }
        });

        $booking->refresh()->loadMissing(['client', 'itineraries.product', 'passengers']);
        $this->sendConfirmationEmail($booking);
    }

    public function releaseExpiredUnpaidReservations(): int
    {
        $cutoff = now()->subMinutes($this->unpaidHoldMinutes());
        $pendingStatusIds = Status::query()
            ->where('statusable', Booking::class)
            ->whereIn('slug', [Status::BOOKING_PENDING, Status::BOOKING_PENDING_PAYMENT])
            ->pluck('id');

        $bookingIds = Booking::query()
            ->whereIn('status_id', $pendingStatusIds)
            ->where('created_at', '<=', $cutoff)
            ->whereHas('itineraries')
            ->pluck('id');

        $released = 0;

        foreach ($bookingIds as $bookingId) {
            if ($this->releaseUnpaidReservation((int) $bookingId)) {
                $released++;
            }
        }

        return $released;
    }

    public function releaseIfUnpaidHoldElapsed(Booking $booking): bool
    {
        if (!$booking->hasStatusSlug(Status::BOOKING_PENDING, Status::BOOKING_PENDING_PAYMENT)) {
            return false;
        }

        if ($booking->created_at === null || $booking->created_at->gt(now()->subMinutes($this->unpaidHoldMinutes()))) {
            return false;
        }

        return $this->releaseUnpaidReservation($booking->id);
    }

    public function findByLocatorAndEmail(string $locator, string $email): ?Booking
    {
        return Booking::query()
            ->where('external_ref', $locator)
            ->whereHas('client', function ($query) use ($email) {
                $query->where('email', $email);
            })
            ->with(['client', 'passengers', 'itineraries.product', 'statusRecord', 'payments'])
            ->first();
    }

    private function releaseUnpaidReservation(int $bookingId): bool
    {
        $booking = Booking::query()->with('payments')->find($bookingId);

        if (!$booking || !$booking->hasStatusSlug(Status::BOOKING_PENDING, Status::BOOKING_PENDING_PAYMENT)) {
            return false;
        }

        $stripePayment = $booking->payments
            ->where('type_id', Type::idFor(Payment::class, Type::PAID_BY_STRIPE))
            ->whereNotNull('transaction_id')
            ->sortByDesc('id')
            ->first();

        if ($stripePayment && $this->settleOpenPaymentIntent($booking, $stripePayment) !== 'release') {
            return false;
        }

        return DB::transaction(function () use ($bookingId) {
            $locked = Booking::query()->whereKey($bookingId)->lockForUpdate()->first();

            if (!$locked || !$locked->hasStatusSlug(Status::BOOKING_PENDING, Status::BOOKING_PENDING_PAYMENT)) {
                return false;
            }

            $this->restoreReservedSeats($locked);

            $locked->payments()
                ->where('status_id', Status::idFor(Payment::class, Status::PAYMENT_PENDING))
                ->update(['status_id' => Status::idFor(Payment::class, Status::PAYMENT_CANCELLED)]);

            $locked->update([
                'status_id' => Status::idFor(Booking::class, Status::BOOKING_CANCELLED),
            ]);

            Log::info('Released unpaid reservation.', ['booking_id' => $locked->id]);

            return true;
        });
    }

    private function settleOpenPaymentIntent(Booking $booking, Payment $payment): string
    {
        try {
            $this->configureStripe();
            $intent = PaymentIntent::retrieve($payment->transaction_id);
        } catch (\Throwable $e) {
            Log::warning('Could not retrieve PaymentIntent for an unpaid reservation.', [
                'booking_id' => $booking->id,
                'transaction_id' => $payment->transaction_id,
                'exception' => $e,
            ]);

            return 'wait';
        }

        if ($intent->status === Status::PAYMENT_STRIPE_SUCCEEDED) {
            $this->confirmSuccessfulPayment($booking, $intent->id, (int) $intent->amount);

            return 'paid';
        }

        if (in_array($intent->status, ['processing', 'requires_capture'], true)) {
            return 'wait';
        }

        if ($intent->status !== 'canceled') {
            try {
                $intent->cancel();
            } catch (\Throwable $e) {
                return $this->resolvePaymentIntentAfterCancelFailure($booking, $payment, $e);
            }
        }

        return 'release';
    }

    private function resolvePaymentIntentAfterCancelFailure(Booking $booking, Payment $payment, \Throwable $error): string
    {
        try {
            $intent = PaymentIntent::retrieve($payment->transaction_id);
        } catch (\Throwable $e) {
            Log::warning('Could not cancel PaymentIntent for an unpaid reservation.', [
                'booking_id' => $booking->id,
                'transaction_id' => $payment->transaction_id,
                'exception' => $error,
            ]);

            return 'wait';
        }

        if ($intent->status === Status::PAYMENT_STRIPE_SUCCEEDED) {
            $this->confirmSuccessfulPayment($booking, $intent->id, (int) $intent->amount);

            return 'paid';
        }

        if ($intent->status === 'canceled') {
            return 'release';
        }

        Log::warning('Could not cancel PaymentIntent for an unpaid reservation.', [
            'booking_id' => $booking->id,
            'transaction_id' => $payment->transaction_id,
            'status' => $intent->status,
            'exception' => $error,
        ]);

        return 'wait';
    }

    public function cancelPaidReservation(Booking $booking, ?int $userId = null): Invoice
    {
        $booking->loadMissing('payments');

        if (!$booking->canCancelAndRefund()) {
            throw new DomainException('Esta reserva no se puede reembolsar.');
        }

        if (round((float) $booking->total_price, 2) <= 0) {
            throw new DomainException('El importe de la reserva debe ser mayor que cero.');
        }

        $payment = $booking->refundableStripePayment();
        if ($payment === null) {
            throw new DomainException('Esta reserva no tiene un pago de Stripe que reembolsar.');
        }

        $refundId = $this->refundStripeCharge($payment);

        return DB::transaction(function () use ($booking, $payment, $refundId, $userId) {
            $locked = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            if (!$locked->hasStatusSlug(
                Status::BOOKING_PAID,
                Status::BOOKING_CONFIRMED,
                Status::BOOKING_COMPLETED,
            )) {
                throw new DomainException('Esta reserva ya no se puede reembolsar.');
            }

            $this->restoreReservedSeats($locked);

            $locked->load(['client', 'invoices.creditNotes']);
            if ($locked->openPositiveInvoice() === null) {
                $this->invoices->issueForBooking($locked, $userId);
                $locked->unsetRelation('invoices');
                $locked->load(['invoices.creditNotes']);
            }

            $locked->update([
                'status_id' => Status::idFor(Booking::class, Status::BOOKING_REFUNDED),
            ]);
            $locked->unsetRelation('status');

            Payment::query()->whereKey($payment->id)->lockForUpdate()->update([
                'status_id' => Status::idFor(Payment::class, Status::PAYMENT_REFUNDED),
                'refund_id' => $refundId,
            ]);

            $invoice = $locked->openPositiveInvoice();
            if ($invoice === null) {
                throw new DomainException('No hay una factura que abonar.');
            }

            return $this->invoices->issueCreditNote($locked, $invoice, $userId);
        });
    }

    private function refundStripeCharge(Payment $payment): string
    {
        try {
            $this->configureStripe();
            $intent = PaymentIntent::retrieve((string) $payment->transaction_id);
        } catch (\Throwable $e) {
            Log::warning('Could not retrieve PaymentIntent to refund a booking.', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'exception' => $e,
            ]);

            throw new DomainException('No se ha podido consultar el pago en Stripe.');
        }

        if ($intent->status !== Status::PAYMENT_STRIPE_SUCCEEDED) {
            throw new DomainException('El pago de Stripe no está cobrado y no se puede reembolsar.');
        }

        $amount = (int) $intent->amount;
        $alreadyRefunded = (int) ($intent->amount_refunded ?? 0);
        if ($amount > 0 && $alreadyRefunded >= $amount) {
            return $this->existingRefundId((string) $intent->id);
        }

        try {
            $refund = Refund::create([
                'payment_intent' => $intent->id,
            ]);
        } catch (ApiErrorException $e) {
            if ($e->getStripeCode() === 'charge_already_refunded') {
                return $this->existingRefundId((string) $intent->id);
            }

            Log::warning('Stripe refused the booking refund.', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'exception' => $e,
            ]);

            throw new DomainException('Stripe no ha podido reembolsar el pago.');
        }

        if (!in_array($refund->status, ['succeeded', 'pending'], true)) {
            throw new DomainException('Stripe no ha completado el reembolso.');
        }

        return (string) $refund->id;
    }

    private function existingRefundId(string $paymentIntentId): string
    {
        $refunds = Refund::all([
            'payment_intent' => $paymentIntentId,
            'limit' => 1,
        ]);

        $refundId = $refunds->data[0]->id ?? null;
        if (!$refundId) {
            throw new DomainException('El pago ya está reembolsado en Stripe, pero no se encontró el reembolso.');
        }

        return (string) $refundId;
    }

    private function restoreReservedSeats(Booking $booking): void
    {
        $quantity = $booking->passengers()->count();
        $itinerary = $booking->itineraries()->orderByPivot('itinerary_order')->first();

        if (!$itinerary || $quantity < 1) {
            return;
        }

        $itinerary = Itinerary::query()->whereKey($itinerary->id)->lockForUpdate()->first();
        if (!$itinerary) {
            return;
        }

        $room = max(0, (int) $itinerary->total_stock - (int) $itinerary->available_stock);
        $restored = min($quantity, $room);

        if ($restored > 0) {
            $itinerary->increment('available_stock', $restored);
        }
    }

    private function unpaidHoldMinutes(): int
    {
        return max(1, (int) config('reservations.unpaid_hold_minutes', 15));
    }

    private function configureStripe(): void
    {
        Stripe::setApiKey((string) config('services.stripe.secret'));
    }

    private function resolveClient(array $validated): Client
    {
        $existing = Client::query()
            ->where('email', $validated['customer_email'])
            ->first();

        if ($existing) {
            return $existing;
        }

        $attributes = [
            'name' => $validated['customer_name'],
            'last_name' => $validated['customer_last_name'],
            'email' => $validated['customer_email'],
            'phone' => $validated['customer_phone'] ?? null,
            'dni_passport' => $validated['customer_document'],
            'nationality' => $validated['customer_nationality'],
            'status_id' => Status::idFor(Client::class, Status::CLIENT_ACTIVE),
        ];

        $pax1 = $validated['passengers'][1] ?? null;
        if (
            is_array($pax1)
            && isset($pax1['birth_date'], $pax1['document'])
            && strtoupper(trim($validated['customer_document'])) === strtoupper(trim($pax1['document']))
        ) {
            $attributes['date_of_birth'] = $pax1['birth_date'];
        }

        return Client::create($attributes);
    }

    private function createBooking(int $clientId): Booking
    {
        return Booking::create([
            'client_id' => $clientId,
            'external_ref' => 'LOC-'.Str::upper(Str::random(8)),
            'status_id' => Status::idFor(Booking::class, Status::BOOKING_PENDING),
            'total_price' => 0,
            'currency' => 'EUR',
        ]);
    }

    private function createPassengersAndCalculateBookingPrice(array $validated, Itinerary $itinerary, int $bookingId): float
    {
        $totalBookingPrice = 0;

        foreach ($validated['passengers'] as $passenger) {
            $age = \Carbon\Carbon::parse($passenger['birth_date'])->age;
            $typeId = Passenger::getPassengerTypeIdByAge($age);

            $priceRow = $itinerary->itineraryPrices()
                ->where('passenger_type_id', $typeId)
                ->first();

            $price = $priceRow ? $priceRow->price : $itinerary->price;
            $taxes = $priceRow ? $priceRow->taxes : $itinerary->taxes;

            Passenger::create([
                'booking_id' => $bookingId,
                'name' => $passenger['first_name'],
                'last_name' => $passenger['last_name'],
                'date_of_birth' => $passenger['birth_date'],
                'dni_passport' => $passenger['document'],
                'nationality' => $passenger['nationality'],
                'gender' => $passenger['gender'],
                'passenger_type_id' => $typeId,
                'status_id' => Status::idFor(Client::class, Status::CLIENT_ACTIVE),
                'price_at_booking' => $price,
                'taxes_at_booking' => $taxes,
            ]);

            $totalBookingPrice += ($price + $taxes);
        }

        return round($totalBookingPrice, 2);
    }

    private function syncBookingMetaFromReservationForm(Booking $booking, ?string $customerNotes): void
    {
        $booking->unsetRelation('metaData');
        $booking->loadMissing('metaData');
        $meta = $booking->metaData?->meta_data ?? [];
        if (!is_array($meta)) {
            $meta = [];
        }
        $meta['customer_notes'] = $customerNotes !== null && $customerNotes !== '' ? trim($customerNotes) : '';
        if (!array_key_exists('internal_notes', $meta)) {
            $meta['internal_notes'] = '';
        }
        $booking->metaData()->updateOrCreate([], ['meta_data' => $meta]);
    }

    private function sendConfirmationEmail(Booking $booking): void
    {
        $email = $booking->client?->email;
        if (!$email) {
            return;
        }

        try {
            Mail::to($email)->send(new BookingConfirmed($booking));
        } catch (\Throwable $e) {
            Log::error('Failed to send booking confirmation email.', [
                'booking_id' => $booking->id,
                'exception' => $e,
            ]);
        }
    }
}
