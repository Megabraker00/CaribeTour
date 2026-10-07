<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    private const VIEW_AS_TOUR = 'tour';

    private const VIEW_AS_PRODUCT = 'product';

    public function __construct(private readonly ReservationService $reservations)
    {
    }

    public function create(Product $product, Itinerary $itinerary)
    {
        $this->reservations->assertItineraryBelongsToProduct($product, $itinerary);

        return view('reservation.new', $this->summaryData($product, $itinerary, self::VIEW_AS_TOUR));
    }

    public function store(StoreReservationRequest $request, Product $product, Itinerary $itinerary)
    {
        $this->reservations->createReservation($request->validated(), $product, $itinerary);

        return redirect()
            ->route('reservation.payment', [$product, $itinerary])
            ->with('success', 'Reserva creada correctamente.');
    }

    public function payment(Product $product, Itinerary $itinerary)
    {
        $booking = $this->reservations->bookingFromSession($product, $itinerary);

        if ($this->reservations->releaseIfUnpaidHoldElapsed($booking)) {
            session()->forget('booking_id');

            return redirect()
                ->route('reservation.create', [$product, $itinerary])
                ->with('error', 'La reserva ha caducado y las plazas se han liberado. Vuelve a intentarlo.');
        }

        $paymentIntent = $this->reservations->getOrCreatePaymentIntent($booking, $product);

        return view('reservation.payment', array_merge(
            $this->summaryData($product, $itinerary, self::VIEW_AS_PRODUCT),
            [
                'booking' => $booking,
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => config('services.stripe.key'),
            ]
        ));
    }

    public function paymentCallback(Request $request, Product $product, Itinerary $itinerary)
    {
        try {
            $booking = $this->reservations->bookingFromSession($product, $itinerary);
            $paymentIntentId = $request->query('payment_intent');

            if (!$paymentIntentId) {
                return redirect()
                    ->route('reservation.payment', [$product->slug, $itinerary->id])
                    ->with('error', 'No se encontró información del pago.');
            }

            $intent = $this->reservations->retrievePaymentIntent($paymentIntentId);

            abort_unless((int) ($intent->metadata->booking_id ?? 0) === $booking->id, 404);

            $viewData = array_merge(
                $this->summaryData($product, $itinerary, self::VIEW_AS_PRODUCT),
                ['booking' => $booking]
            );

            if ($intent->status === Status::PAYMENT_STRIPE_SUCCEEDED) {
                $this->reservations->confirmSuccessfulPayment(
                    $booking,
                    $intent->id,
                    (int) $intent->amount
                );
                session()->forget('booking_id');

                return view('reservation.payment_ok', $viewData);
            }

            return view('reservation.payment_no_ok', $viewData);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return redirect()
                ->route('reservation.payment', [$product->slug, $itinerary->id])
                ->with('error', '[ERROR]: Vuelva a intentar');
        }
    }

    private function summaryData(Product $product, Itinerary $itinerary, string $productKey): array
    {
        $summary = $itinerary->reservableSummary();
        $days = $summary['days'];
        $nights = $summary['nights'];
        $departure = $summary['departure'];
        $return = $summary['return'];
        $price = $itinerary->fullPrice();

        if ($productKey === self::VIEW_AS_TOUR) {
            return [
                'tour' => $product,
                'itinerary' => $itinerary,
                'tourDeparture' => $departure,
                'tourReturn' => $return,
                'days' => $days,
                'nights' => $nights,
                'price' => $price,
            ];
        }

        return [
            'product' => $product,
            'itinerary' => $itinerary,
            'productDeparture' => $departure,
            'productReturn' => $return,
            'days' => $days,
            'nights' => $nights,
            'price' => $price,
        ];
    }
}
