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
}
