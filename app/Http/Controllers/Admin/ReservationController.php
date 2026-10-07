<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePassengerRequest;
use App\Models\Booking;
use App\Models\Passenger;
use App\Services\ReservationService;
use Carbon\Carbon;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function __construct(private readonly ReservationService $reservations)
    {
    }
    public function index()
    {
        return view('admin.booking.index');
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'client',
            'statusRecord',
            'metaData',
            'payments.type',
            'payments.statusRecord',
            'passengers.type',
            'invoices.createdUser',
            'invoices.creditNotes',
            'invoices.rectifiedInvoice',
            'itineraries' => function ($q) {
                $q->orderBy('booking_itinerary.itinerary_order')
                    ->with(['product', 'departure_t', 'arrival_t', 'segments' => function ($sq) {
                        $sq->orderBy('sort_order')->with(['departureTerminal', 'arrivalTerminal', 'type']);
                    }]);
            },
        ]);

        return view('admin.booking.show', ['booking' => $booking]);
    }

    /**
     * Actualiza internal_notes en meta_data sin pisar customer_notes.
     */
    public function updateMeta(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'internal_notes' => 'nullable|string|max:5000',
        ], [
            'internal_notes.max' => 'Las notas internas no pueden superar :max caracteres.',
        ]);

        $booking->unsetRelation('metaData');
        $booking->loadMissing('metaData');
        $meta = $booking->metaData?->meta_data ?? [];
        if (!is_array($meta)) {
            $meta = [];
        }
        if (!array_key_exists('customer_notes', $meta)) {
            $meta['customer_notes'] = '';
        }
        $meta['internal_notes'] = isset($validated['internal_notes']) ? trim((string) $validated['internal_notes']) : '';

        $booking->metaData()->updateOrCreate([], ['meta_data' => $meta]);

        return redirect()
            ->route('admin.booking.show', $booking)
            ->with('success', 'Notas internas guardadas.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        try {
            $credit = $this->reservations->cancelPaidReservation($booking, auth()->id());
        } catch (DomainException $exception) {
            return redirect()
                ->route('admin.booking.show', $booking)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.booking.show', $booking)
            ->with('success', 'Reserva reembolsada y plazas liberadas. Abono '.$credit->number.' emitido.');
    }

    public function editPassenger(Booking $booking, Passenger $passenger): View
    {
        $this->ensurePassengerBelongsToBooking($booking, $passenger);

        return view('admin.booking.passenger-edit', [
            'booking' => $booking,
            'passenger' => $passenger,
        ]);
    }

    public function updatePassenger(UpdatePassengerRequest $request, Booking $booking, Passenger $passenger): RedirectResponse
    {
        $this->ensurePassengerBelongsToBooking($booking, $passenger);

        $validated = $request->validated();
        $validated['passenger_type_id'] = Passenger::getPassengerTypeIdByAge(
            Carbon::parse($validated['date_of_birth'])->age
        );

        $passenger->update($validated);

        return redirect()
            ->route('admin.booking.show', $booking)
            ->with('success', 'Pasajero actualizado correctamente.');
    }

    private function ensurePassengerBelongsToBooking(Booking $booking, Passenger $passenger): void
    {
        if ((int) $passenger->booking_id !== (int) $booking->id) {
            abort(404);
        }
    }
}
