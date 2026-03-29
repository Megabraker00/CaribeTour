<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryPrice;

class ItineraryController extends Controller
{
    public function destroyTourDate(int $id)
    {
        $date = Itinerary::findOrFail($id);
        $productId = $date->product_id;

        $date->bookings()->detach();
        ItineraryPrice::query()->where('itinerary_id', $date->id)->delete();
        $date->segments()->delete();
        $date->delete();

        return redirect()->to(route('admin.tour.show', $productId).'#tour-itineraries')
            ->with('success', 'Fecha de salida eliminada.');
    }
}
