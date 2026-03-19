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
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva reserva
     */
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

    /**
     * Almacena los datos de la reserva, el titular y los pasajeros y redije a la ruta que muestra el formulario de stripe
     */
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

            session(['booking_id' => $booking->id]);

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
            'status_id' => Status::BOOKING_PENDING,
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

    /**
     * Muestra los datos de la reserva y el formulario de stripe
     */
    public function payment(Request $request, Product $product, Itinerary $itinerary)
    {    
        $bookingId = session('booking_id');
        
        $booking = Booking::findOrFail($bookingId);

        Stripe::setApiKey(env('STRIPE_SECRET_KEY_TEST'));

        // 2. Crear el Intento de Pago
        $paymentIntent = PaymentIntent::create([
            'amount' => $booking->total_price * 100, // Stripe usa céntimos (10€ = 1000)
            'currency' => strtolower($booking->currency),
            'metadata' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'booking_id' => $booking->id,
                'external_ref' => $booking->external_ref
            ],
        ]);  

        $days = $itinerary?->days;
        $nights = $itinerary?->nights;
        $departure = $itinerary->firstSegment()->departure_date;
        $return = $itinerary->lastSegment()->departure_date;
        $price = $itinerary->fullPrice();

        return view('reservation.payment', [
            'product' => $product,
            'itinerary' => $itinerary,
            'booking' => $booking,
            'clientSecret' => $paymentIntent->client_secret, // Clave para el JS
            'productDeparture' => $departure,
            'productReturn' => $return,
            'days' => $days,
            'nights' => $nights,
            'price' => $price,
        ]);
    }

    /**
     * procesa si el pago se ha efectuado correctamente y muestra la pagina de exito o fracaso según el caso
     */
    public function paymentCallback(Request $request, Product $product, Itinerary $itinerary)
    {
        
        // 1. Configuramos la llave secreta
        Stripe::setApiKey(env('STRIPE_SECRET_KEY_TEST'));

        try {
            // 2. Recuperamos el ID del pago que Stripe nos manda por la URL
            $paymentIntentId = $request->query('payment_intent');
            
            if (!$paymentIntentId) {
                
                return redirect()->route('reservation.payment', [
                        $product->slug, 
                        $itinerary->id,
                    ])->with('error', 'No se encontró información del pago.');
            }
            
            // 3. Consultamos el estado real en los servidores de Stripe
            $intent = PaymentIntent::retrieve($paymentIntentId);
            
            // 4. Recuperamos la reserva de la base de datos (usando el metadata que enviamos al crear el intent)
            $booking = Booking::findOrFail($intent->metadata->booking_id);

            // Variables comunes para las vistas
            $days = $itinerary->days;
            $nights = $itinerary->nights;
            $productDeparture = $itinerary->firstSegment()->departure_date;
            $productReturn = $itinerary->lastSegment()->departure_date;
            $price = $itinerary->fullPrice();

            // --- CASO ÉXITO ---
            if ($intent->status === Status::PAYMENT_STRIPE_SUCCEEDED) {
                
                // Si la reserva aún no está pagada en nuestra DB, la actualizamos
                if ($booking->status_id != Status::PAYMENT_PAID) {
                    $booking->update(['status_id' => Status::PAYMENT_PAID]);
                    
                    // Registramos el pago exitoso
                    $booking->payments()->create([
                        'amount' => $intent->amount / 100,
                        'transaction_id' => $intent->id,
                        'status_id' => Status::PAYMENT_PAID,// Exitoso
                        'type_id' => Type::PAID_BY_STRIPE,
                    ]);
                }

                session()->forget('booking_id'); // Limpiamos sesión

                return view('reservation.payment_ok', compact('product', 'itinerary', 'booking', 'price', 'days', 'nights', 'productDeparture', 'productReturn'));
            }

            // --- CASO ERROR / CANCELADO ---
            // Si el pago no fue 'succeeded', registramos el intento fallido (opcional)
            return view('reservation.payment_no_ok', compact('product', 'itinerary', 'booking', 'price', 'days', 'nights', 'productDeparture', 'productReturn'));

        } catch (\Exception $e) {
            // Si algo falla técnicamente (error de API, etc.)
            //dd($e);
            Log::error($e->getMessage(), ['exception' => $e]);
            return redirect()->route('reservation.payment', [$product->slug, $itinerary->id])
                            ->with('error', '[ERROR]: Vuelva a intentar');
        }
    }
}
