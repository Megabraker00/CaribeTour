<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class DatatableController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function bookings()
    {

        $bookings = Booking::select('id', 'status_id', 'total_amount', DB::raw('"R titular" as titular'), DB::raw('"15/06/24" as f_salida'))->get();

        //dd($bookings);

        return DataTables::make($bookings)->toJson();
    }

    public function clients()
    {
        $clients = Client::select('id', 'name', 'last_name', 'dni_passport', 'booking_id');

        return DataTables::make($clients)->toJson();
    }
}
