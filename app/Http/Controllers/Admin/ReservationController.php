<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
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
        if (! is_array($meta)) {
            $meta = [];
        }
        if (! array_key_exists('customer_notes', $meta)) {
            $meta['customer_notes'] = '';
        }
        $meta['internal_notes'] = isset($validated['internal_notes']) ? trim((string) $validated['internal_notes']) : '';

        $booking->metaData()->updateOrCreate([], ['meta_data' => $meta]);

        return redirect()
            ->route('admin.booking.show', $booking)
            ->with('success', 'Notas internas guardadas.');
    }
}
