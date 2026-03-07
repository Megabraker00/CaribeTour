<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /*
    public function create(Request $request, $producSlug)
    {
        $product = Product::query()
            ->where('slug', $producSlug)
            ->where('status_id', Status::TOUR_ACTIVE)
            ->where('type_id', Type::TOUR)
            ->firstOrFail();

        // TODO: redirigir a un vista que diga "El producto parece que ya no estar disponible"
        if ($product === null) {
            abort(404);
        }

        $data = $request->validate([
            'idIt' => ['required', 'integer', 'exists:itineraries,id']
        ]);
        $itineraryId = $data['idIt'];

        $itinerary = $product->itineraries()
            ->where('id', $itineraryId)
            ->firstOrFail();

        $days = $itinerary?->days;
        $nights = $itinerary?->nights;
        $departure = $itinerary->firstSegment()->departure_date;
        $return = $itinerary->lastSegment()->departure_date;
        $price = $itinerary->fullPrice();

        //$itinerary = Itinerary::where('id', $request->idIt)->first();

        //dd($product);

        //$itinerary = Itinerary::findOrFail($request->query('idD'));

        //return view('reservation.new', ['product' => $product, 'itinerary' => $itinerary]);
        return view('reservation.new', [
            'tour' => $product,
            'tourDeparture' => $departure,
            'tourReturn' => $return,
            'days' => $days,
            'nights' => $nights,
            'price' => $price,
        ]);
    }
        */

    public function store(Request $request, Product $product, Itinerary $itinerary)
    {


        /*
        |--------------------------------------------------------------------------
        | 1. VALIDACIÓN
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'itId' => ['required','integer','exists:itineraries,id'],
            'quantity' => ['required','integer','min:1','max:20'],

            'customer_name' => ['required','string','max:100'],
            'customer_last_name' => ['required','string','max:100'],
            'customer_nationality' => ['required','string','max:100'],
            'customer_document' => ['required','string','max:50'],
            'customer_email' => ['required','email','max:150'],
            'customer_phone' => ['nullable','string','max:30'],

            'passengers' => ['required','array'],
            'passengers.*.first_name' => ['required','string','max:100'],
            'passengers.*.last_name' => ['required','string','max:100'],
            'passengers.*.nationality' => ['required','string','max:100'],
            'passengers.*.document' => ['required','string','max:50'],
            'passengers.*.gender' => ['required','in:male,female'],
            'passengers.*.birth_date' => ['required','date','before:today'],

            'notes' => ['nullable','string','max:1000']
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. VERIFICAR PRODUCTO ACTIVO
        |--------------------------------------------------------------------------
        */

        if (!$product->is_active) {
            abort(404, 'Producto no disponible');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. VERIFICAR ITINERARIO
        |--------------------------------------------------------------------------
        */

        if ($itinerary->product_id !== $product->id) {
            abort(404);
        }

        if (!$itinerary->is_active) {
            abort(404, 'Itinerario no disponible');
        }

        /*
        |--------------------------------------------------------------------------
        | 4. VERIFICAR DISPONIBILIDAD
        |--------------------------------------------------------------------------
        */

        $availableSeats = $itinerary->capacity - $itinerary->booked_seats;

        if ($validated['quantity'] > $availableSeats) {
            return back()->withErrors([
                'quantity' => 'No quedan suficientes plazas disponibles.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. GUARDAR TODO EN TRANSACCIÓN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $product, $itinerary) {

            /*
            |---------------------------------
            | Crear booking
            |---------------------------------
            */

            $booking = Booking::create([
                'product_id' => $product->id,
                'status_id' => 1, // pending
                'customer_name' => $validated['customer_name'],
                'customer_last_name' => $validated['customer_last_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'] ?? null
            ]);

            /*
            |---------------------------------
            | Guardar itinerario reservado
            |---------------------------------
            */

            $booking->itineraries()->attach($itinerary->id, [
                'price_at_booking' => $itinerary->price,
                'taxes_at_booking' => $itinerary->taxes,
                'pax' => $validated['quantity'],
                'itinerary_order' => 1
            ]);

            /*
            |---------------------------------
            | Guardar pasajeros
            |---------------------------------
            */

            foreach ($validated['passengers'] as $passenger) {

                Passenger::create([
                    'booking_id' => $booking->id,
                    'first_name' => $passenger['first_name'],
                    'last_name' => $passenger['last_name'],
                    'nationality' => $passenger['nationality'],
                    'document' => $passenger['document'],
                    'gender' => $passenger['gender'],
                    'birth_date' => $passenger['birth_date']
                ]);

            }

            /*
            |---------------------------------
            | Actualizar plazas ocupadas
            |---------------------------------
            */

            $itinerary->increment('booked_seats', $validated['quantity']);

        });

        /*
        |--------------------------------------------------------------------------
        | 6. REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()->route('reservation.success');




        /*dd($request);
        try {
            // validate form
            $request->validate(
                [
                    "nombreT" => "required",
                    "apellidosT" => "required",
                    "dniT" => "required",
                    "telefono" => "required",
                    "mail" => "required",
                ]
            );

            // validate itinerary is still available
            $itinerary = Itinerary::findOrFail($request->idD);

            DB::beginTransaction();

            // create a new reservation
            $booking = $this->createNewReservation($request);

            // link reservation to itinerary
            $booking->itineraries()->attach($itinerary, ['itinerary_order' => 1]);

            // store reservation holder
            $this->createClientReservation($request, $booking->id);

            // store reservation passengers
            $this->createPassengersReservation($request, $booking->id);

            DB::commit();

            // redirect to payment
            return redirect()->route('payment', [
                'producto' => $producSlug,
                'idB' => $booking->id,
                'idIt' => $request->idIt,
                'idP' => $request->formaPago,
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
            */
    }

    public function payment(Request $request, $producSlug)
    {
        $request->validate([
            'idB' => 'required',
            'idIt' => 'required',
            'idP' => 'required',
        ]);

        $product = Product::where('slug', $producSlug)->first();

        return view('reservation.payment', ['product' => $product]);
    }

    private function createNewReservation(Request $request)
    {
        $booking = new Booking();
        $booking->payment_type_id = $request->formaPago; // validar que sea una forma de pago existente (con una funcion y dentro switch case o posición de array)
        $booking->total_amount = $request->totalAmount;
        $booking->status_id = Status::BOOKING_PENDING_PAYMENT;
        $booking->save();

        return $booking;
    }

    private function createClientReservation($request, $booking_id)
    {
        // validar los datos del cliente (tipos, longitud, requeridos)
        $client = new Client();
        $client->name = $request->customer_name;
        $client->last_name = $request->customer_last_name;
        $client->dni_passport = $request->dniT;
        $client->email = $request->customer_email;
        $client->type_id = Type::HOLDER;
        $client->status_id = Status::CLIENT_ACTIVE;
        $client->booking_id = $booking_id;
        $client->save();

        return $client;
    }

    private function createPassengersReservation($request, $booking_id)
    {
        $ret = [];

        for ($i = 0; $i <= 1; $i++) {
            $p = new Client();
            $p->name = $request->nombreP[$i];
            $p->last_name = $request->apellidosP[$i];
            $p->dni_passport = $request->dniP[$i];
            $birthdate = date('d/m/Y H:i:s', strtotime("{$request->diaP[$i]}.{$request->mesP[$i]}.{$request->anioP[$i]}"));
            //$p->meta()->create(['meta_dataable_id' => $p->id, 'meta_data' => json_encode(['birthdate' => $birthdate])]);
            $p->booking_id = $booking_id;
            $p->type_id = Type::PASSENGER;
            $p->status_id = Status::CLIENT_ACTIVE;
            $p->save();
            $ret[] = $p;
        }

        return $ret;
    }
}
