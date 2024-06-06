<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Product;

class ItineraryController extends Controller
{
    public function storeTourDate(Request $request)
    {
        $validatedFields = $request->validate([
            'departure_date' => 'required|date',
            'departure_terminal_id' => 'required|integer',
            'arrival_date' => 'required|date|after:departure_date',
            'arrival_terminal_id' => 'required|integer',
            'price' => 'required',
            'taxes' => 'required',
            'product_id' => 'required',
        ]);

        Itinerary::create($validatedFields);

        return redirect()->route('admin.tour.show', $request->product_id);
    }

    public function destroyTourDate(Request $request)
    {
        $date = Itinerary::findOrFail($request->date_id);
        $product_id = $date->product_id;
        $date->delete();

        return redirect()->route('admin.tour.show', $product_id);
    }
}
