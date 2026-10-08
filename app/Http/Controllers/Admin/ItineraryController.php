<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryPrice;
use App\Models\Product;
use App\Support\ProductCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ItineraryController extends Controller
{
    public function updateStock(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $validated = $request->validate([
            'total_stock' => 'required|integer|min:0|max:9999',
        ], [
            'total_stock.required' => 'Indica el número de plazas.',
            'total_stock.integer' => 'Las plazas deben ser un número entero.',
            'total_stock.min' => 'Las plazas no pueden ser negativas.',
            'total_stock.max' => 'Las plazas no pueden superar 9999.',
        ]);

        DB::transaction(function () use ($itinerary, $validated) {
            $locked = Itinerary::query()->whereKey($itinerary->id)->lockForUpdate()->firstOrFail();
            $sold = $locked->soldSeats();
            $total = (int) $validated['total_stock'];

            if ($total < $sold) {
                throw ValidationException::withMessages([
                    'total_stock' => "Hay {$sold} plazas vendidas. El stock total no puede ser menor.",
                ]);
            }

            $locked->update([
                'total_stock' => $total,
                'available_stock' => $total - $sold,
            ]);
        });

        return redirect()
            ->route('admin.itineraries.segments.index', $itinerary)
            ->with('success', 'Plazas actualizadas.');
    }

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
