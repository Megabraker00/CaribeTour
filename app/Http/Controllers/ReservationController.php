<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Date;
use App\Models\Product;
use App\Models\State;
use App\Models\Type;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function create(Request $request, $producSlug)
    {
        $product = Product::where('slug', $producSlug)->first();

        $date = Date::find($request->query('idF'));

        return view('reservation.new', ['product' => $product, 'date' => $date]);
    }

    public function store(Request $request, $producSlug)
    {
        try {
            // validate form
            //$request->validate();

            // validate date is still available
            $date = Date::find($request->idF);
            if (!$date) {
                throw new Exception("date not available");
            }

            DB::beginTransaction();

            // create a new reservation
            $booking = new Booking();
            $booking->payment_type_id = $request->formaPago; // validar que sea una forma de pago existente (con una funcion y dentro switch case o posición de array)
            $booking->total_amount = $request->totalAmount;
            $booking->state_id = State::BOOKING_PENDING_PAYMENT;
            $booking->save();

            // link resercation to date
            $booking->dates()->attach($date);

            // store reservation holder
            $client = new Client();
            $client->name = $request->nombreT;
            $client->last_name = $request->apellidosT;
            $client->dni_passport = $request->dniT;
            $client->type_id = Type::CLIENT_HOLDER;
            $client->state_id = State::CLIENT_ACTIVE;
            $client->booking_id = $booking->id;
            $client->save();

            // store reservation passengers
            for ($i = 0; $i <= 1; $i++) {
                $p = new Client();
                $p->name = $request->nombreP[$i];
                $p->last_name = $request->apellidosP[$i];
                $p->dni_passport = $request->dniP[$i];
                $birthdate = date('d/m/Y H:i:s', strtotime("{$request->diaP[$i]}.{$request->mesP[$i]}.{$request->anioP[$i]}"));
                //$p->meta()->create(['meta_dataable_id' => $p->id, 'meta_data' => json_encode(['birthdate' => $birthdate])]);
                $p->booking_id = $booking->id;
                $p->type_id = Type::CLIENT_PASSENGERS;
                $p->state_id = State::CLIENT_ACTIVE;
                $p->save();
            }

            DB::commit();

            // redirect to payment
            return redirect()->route('payment', [
                'producto' => $producSlug, 
                'idB' => $booking->id, 
                'idF' => $request->idF, 
                'idP' => $request->formaPago
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function payment(Request $request, $producSlug)
    {
        $request->validate([
            'idB' => 'required',
            'idF' => 'required',
            'idP' => 'required',
        ]);

        $product = Product::where('slug', $producSlug)->first();

        return view('reservation.payment', ['product' => $product]);
    }
}
