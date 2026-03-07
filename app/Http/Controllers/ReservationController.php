<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Passenger;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\StoreReservationRequest;

class ReservationController extends Controller
{
    public function create(Product $product, Itinerary $itinerary)
    {
        //abort_unless($itinerary->product_id === $product->id, 404);

        $days = $itinerary?->days;
        $nights = $itinerary?->nights;
        $departure = $itinerary->firstSegment()->departure_date;
        $return = $itinerary->lastSegment()->departure_date;
        $price = $itinerary->fullPrice();

        return view('reservation.new', [
            'tour' => $product,
            'itinerary' => $itinerary,
            'tourDeparture' => $departure,
            'tourReturn' => $return,
            'days' => $days,
            'nights' => $nights,
            'price' => $price,
        ]);

    }

    public function store(StoreReservationRequest $request, Product $product, Itinerary $itinerary)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($request, $product, $itinerary, $validated) {

            $client = $this->createClient($validated);

            $booking = $this->createBooking($client->id);

            $totalBookingPrice = $this->createPassengersAndCalculateBookingPrice($validated, $itinerary, $booking->id);

            $booking->update(['total_price' => $totalBookingPrice]);
                
            $itinerary->decrement('available_stock', $request->quantity);

            $booking->itineraries()->attach($itinerary->id, [
                'itinerary_order' => 1
            ]);

            return redirect()->route('reservation.payment', [$product, $itinerary])
                                ->with('success', 'Reserva creada correctamente.');

        });        
    }

    private function createClient(array $validated)
    {
        $client = Client::updateOrCreate(
            ['email' => $validated['customer_email']],
            [
                'name' => $validated['customer_name'],
                'last_name' => $validated['customer_last_name'],
                'phone' => $validated['customer_phone'] ?? null,
                'dni_passport' => $validated['customer_document'],
                'nationality' => $validated['customer_nationality'],
                'status_id' => Status::CLIENT_ACTIVE,
            ]
        );

        return $client;
    }

    private function createBooking($clientId) 
    {
        // 3. RESERVA
        $booking = Booking::create([
            'client_id' => $clientId,
            'external_ref' => 'LOC-' . Str::upper(Str::random(8)),
            'status_id' => Status::CLIENT_ACTIVE,
            'total_price' => 0, // Se calcula con createPassengersAndCalculateBookingPrice
            'currency' => 'EUR',
        ]);

        return $booking;
    }

    private function createPassengersAndCalculateBookingPrice($validated, Itinerary $itinerary, $bookingId)
    {
        $totalBookingPrice = 0;

        // 4. PASAJEROS
        foreach ($validated['passengers'] as $passenger) {
            $age = \Carbon\Carbon::parse($passenger['birth_date'])->age;
            $typeId = Passenger::getPassengerTypeIdByAge($age);

            // Buscamos el precio específico para este tipo de pasajero en este itinerario
            $priceRow = DB::table('itinerary_prices')
                ->where('itinerary_id', $itinerary->id)
                ->where('passenger_type_id', $typeId)
                ->first();

            // Fallback al precio base si no existe tarifa especial
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
                'status_id' => Status::CLIENT_ACTIVE,
                'price_at_booking' => $price,
                'taxes_at_booking' => $taxes,
            ]);

            $totalBookingPrice += ($price + $taxes);
        }

        return $totalBookingPrice;
    }

    public function payment(Request $request, Product $product, Itinerary $itinerary)
    {
        return view('reservation.payment', ['product' => $product]);
    }
}
