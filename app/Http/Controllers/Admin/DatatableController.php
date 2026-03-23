<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Itinerary;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class DatatableController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bookings()
    {
        $bookings = Booking::with(['client', 'statusRecord', 'itineraries' => function ($q) {
            $q->orderBy('booking_itinerary.itinerary_order')->with(['segments' => function ($sq) {
                $sq->orderBy('sort_order');
            }]);
        }])
            ->get();

        $rows = $bookings->map(function ($booking) {
            $titular = '—';
            if ($booking->relationLoaded('client') && $booking->client) {
                $titular = trim(($booking->client->name ?? '') . ' ' . ($booking->client->last_name ?? ''));
            }
            if ($titular === '') {
                $titular = '—';
            }

            $fSalida = '—';
            $firstItinerary = $booking->itineraries->first();
            if ($firstItinerary && $firstItinerary->relationLoaded('segments') && $firstItinerary->segments->isNotEmpty()) {
                $firstSegment = $firstItinerary->segments->sortBy('sort_order')->first();
                if ($firstSegment && $firstSegment->departure_date) {
                    $fSalida = \Carbon\Carbon::parse($firstSegment->departure_date)->format('d/m/Y');
                }
            }

            $totalPrice = $booking->total_price !== null ? (float) $booking->total_price : 0;
            $totalAmount = number_format($totalPrice, 2, ',', '.') . ' ' . ($booking->currency ?? 'EUR');

            return [
                'id' => $booking->id,
                'external_ref' => $booking->external_ref ?? '—',
                'titular' => $titular,
                'f_salida' => $fSalida,
                'total_amount' => $totalAmount,
                'status_id' => $booking->status_id,
                'status_name' => $booking->statusRecord->name ?? (string) $booking->status_id,
            ];
        });

        return DataTables::of($rows)->toJson();
    }

    public function invoices()
    {
        $invoices = Invoice::select('id', 'booking_id', 'created_at', 'created_user_id')->get();

        return DataTables::make($invoices)->toJson();
    }

    public function clients()
    {
        $clients = Client::select('id', 'name', 'last_name', 'dni_passport')->get();

        return DataTables::make($clients)->toJson();
    }

    public function tours()
    {
        $tours = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name as name', 'products.status_id as status_id',
                'categories.name as category', DB::raw('"652.25" as precio'))
            ->get();

        /*
        $tours = Product::with(['category' => function($query){
            $query->select('name');
        }])
        ->select('id', 'name', 'status_id', DB::raw('"precio" as precio'))
        ->get();
        */

        return DataTables::make($tours)->toJson();
    }

    public function tourItinerarys($productId)
    {
        $itineraries = Itinerary::query()
            ->where('product_id', $productId)
            ->with([
                'segments' => function ($q) {
                    $q->orderBy('sort_order')->with(['departureTerminal', 'arrivalTerminal']);
                },
            ])
            ->get();

        $rows = $itineraries->map(function (Itinerary $itinerary) {
            $first = $itinerary->segments->first();

            $departureDate = $first?->departure_date;
            $arrivalDate = $first?->arrival_date;

            return [
                'id' => $itinerary->id,
                'departure_date' => $departureDate
                    ? $departureDate->format('Y-m-d H:i:s')
                    : '',
                'departure_t' => [
                    'name' => $first?->departureTerminal?->name ?? '—',
                ],
                'arrival_date' => $arrivalDate
                    ? $arrivalDate->format('Y-m-d H:i:s')
                    : '',
                'arrival_t' => [
                    'name' => $first?->arrivalTerminal?->name ?? '—',
                ],
                'price' => $itinerary->price,
                'taxes' => $itinerary->taxes,
                'total_price' => $itinerary->fullPrice(),
            ];
        });

        return DataTables::of($rows)->toJson();
    }
}
