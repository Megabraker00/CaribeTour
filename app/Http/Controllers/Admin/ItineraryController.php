<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryPrice;
use App\Models\Product;
use App\Support\ProductCatalog;

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

        $product = Product::query()->findOrFail($productId);

        return redirect()->to(ProductCatalog::showUrl($product).'#tour-itineraries')
            ->with('success', 'Fecha de salida eliminada.');
    }
}
