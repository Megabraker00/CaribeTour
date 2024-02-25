<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Product;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create(Request $request, $producSlug)
    {
        $product = Product::where('slug', $producSlug)->first();

        $date = Date::find($request->query('idF'));

        return view('reservation.new', ['product' => $product]);

    }

    public function store(Request $request, $producSlug)
    {
        // validate form
        $request->validate([
            
        ]);

        // store client data
        // create a new reservation
        // link reservation with client

        // redirect to payment
    }

    public function payment(Request $request, $producSlug)
    {
        $product = Product::where('slug', $producSlug)->first();

    }
}
