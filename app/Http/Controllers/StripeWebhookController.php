<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Status;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __construct(private readonly ReservationService $reservations)
    {
    }

    public function handle(Request $request): Response
    {
        $secret = (string) config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $signature = (string) $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload.', ['exception' => $e]);

            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook invalid signature.', ['exception' => $e]);

            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $bookingId = (int) ($intent->metadata->booking_id ?? 0);
            $booking = Booking::query()->find($bookingId);

            if ($booking && $intent->status === Status::PAYMENT_STRIPE_SUCCEEDED) {
                $this->reservations->confirmSuccessfulPayment(
                    $booking,
                    $intent->id,
                    (int) $intent->amount
                );
            }
        }

        return response('ok', 200);
    }
}
