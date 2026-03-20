<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Support\Facades\DB;

class ItineraryController extends Controller
{
    public function destroyTourDate(int $id)
    {
        $date = Itinerary::findOrFail($id);
        $productId = $date->product_id;

        $date->bookings()->detach();
        DB::table('itinerary_prices')->where('itinerary_id', $date->id)->delete();
        $date->segments()->delete();
        $date->delete();

        return redirect()->route('admin.tour.show', $productId)
            ->with('success', 'Fecha de salida eliminada.');
    }
}
