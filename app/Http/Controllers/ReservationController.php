<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Date;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function create(Request $request, $producSlug)
    {
        $product = Product::where('slug', $producSlug)->first();

        $date = Date::findOrFail($request->query('idD'));

        return view('reservation.new', ['product' => $product, 'date' => $date]);
    }

    public function store(Request $request, $producSlug)
    {
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

            // validate date is still available
            $date = Date::findOrFail($request->idD);

            DB::beginTransaction();

            // create a new reservation
            $booking = $this->createNewReservation($request);            

            // link resercation to date
            $booking->dates()->attach($date);

            // store reservation holder
            $this->createClientReservation($request, $booking->id);

            // store reservation passengers
            $this->createPassengersReservation($request, $booking->id);

            DB::commit();

            // redirect to payment
            return redirect()->route('payment', [
                'producto' => $producSlug, 
                'idB' => $booking->id, 
                'idD' => $request->idD, 
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
            'idD' => 'required',
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
        $client = new Client();
        $client->name = $request->nombreT;
        $client->last_name = $request->apellidosT;
        $client->dni_passport = $request->dniT;
        $client->type_id = Type::CLIENT_HOLDER;
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
            $p->type_id = Type::CLIENT_PASSENGERS;
            $p->status_id = Status::CLIENT_ACTIVE;
            $p->save();
            $ret[] = $p;
        }

        return $ret;
    }
}
