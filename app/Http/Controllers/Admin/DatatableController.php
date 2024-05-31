<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
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
        $bookings = Booking::select('id', 'status_id', 'total_amount', DB::raw('"R titular" as titular'), DB::raw('"15/06/24" as f_salida'))->get();

        return DataTables::make($bookings)->toJson();
    }

    public function clients()
    {
        $clients = Client::select('id', 'name', 'last_name', 'dni_passport', 'booking_id')->get();

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
}
