<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingLookupController extends Controller
{
    public function __construct(private readonly ReservationService $reservations)
    {
    }

    public function show(): View
    {
        return view('reservation.lookup');
    }

    public function lookup(Request $request): View
    {
        $validated = $request->validate([
            'external_ref' => 'required|string|max:20',
            'email' => 'required|email|max:150',
        ], [
            'external_ref.required' => 'Introduce el localizador de la reserva.',
            'email.required' => 'Introduce el correo del titular.',
        ]);

        $booking = $this->reservations->findByLocatorAndEmail(
            strtoupper(trim($validated['external_ref'])),
            $validated['email']
        );

        if (!$booking) {
            return view('reservation.lookup')->withErrors([
                'external_ref' => 'No hemos encontrado una reserva con esos datos.',
            ]);
        }

        return view('reservation.lookup', ['booking' => $booking]);
    }
}
