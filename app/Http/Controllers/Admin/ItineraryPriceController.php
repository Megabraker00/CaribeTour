<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryPrice;
use App\Models\Passenger;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItineraryPriceController extends Controller
{
    public function edit(Itinerary $itinerary): View
    {
        $itinerary->load(['product', 'itineraryPrices']);

        $passengerTypes = Type::query()
            ->where('typeable', Passenger::class)
            ->orderBy('id')
            ->get();

        $pricesByType = $itinerary->itineraryPrices->keyBy('passenger_type_id');

        return view('admin.itinerary.prices', [
            'itinerary' => $itinerary,
            'passengerTypes' => $passengerTypes,
            'pricesByType' => $pricesByType,
        ]);
    }

    public function updateBase(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'taxes' => 'required|numeric|min:0',
        ], [
            'price.required' => 'Indica el precio base.',
            'price.numeric' => 'El precio base debe ser un número.',
            'price.min' => 'El precio base no puede ser negativo.',
            'taxes.required' => 'Indica las tasas.',
            'taxes.numeric' => 'Las tasas deben ser un número.',
            'taxes.min' => 'Las tasas no pueden ser negativas.',
        ]);

        $itinerary->update([
            'price' => $validated['price'],
            'taxes' => $validated['taxes'],
        ]);

        return redirect()
            ->route('admin.itineraries.prices.edit', $itinerary)
            ->with('success', 'Tarifa base actualizada.');
    }

    public function update(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $allowedTypeIds = Type::query()
            ->where('typeable', Passenger::class)
            ->pluck('id')
            ->all();

        $rules = [];
        foreach ($allowedTypeIds as $id) {
            $rules["prices.$id.price"] = 'nullable|numeric|min:0';
            $rules["prices.$id.taxes"] = 'nullable|numeric|min:0';
        }

        $request->validate($rules);

        $pricesInput = $request->input('prices', []);

        foreach ($allowedTypeIds as $typeId) {
            $row = $pricesInput[$typeId] ?? [];
            $priceRaw = $row['price'] ?? null;
            $taxesRaw = $row['taxes'] ?? null;

            $priceEmpty = $priceRaw === null || $priceRaw === '';
            $taxesEmpty = $taxesRaw === null || $taxesRaw === '';

            if ($priceEmpty && $taxesEmpty) {
                ItineraryPrice::query()
                    ->where('itinerary_id', $itinerary->id)
                    ->where('passenger_type_id', $typeId)
                    ->delete();

                continue;
            }

            if ($priceEmpty || $taxesEmpty) {
                return redirect()
                    ->route('admin.itineraries.prices.edit', $itinerary)
                    ->withInput()
                    ->withErrors([
                        "prices.{$typeId}" => 'Para cada tipo de pasajero debes indicar precio y tasas, o dejar ambos vacíos para usar la tarifa base del itinerario.',
                    ]);
            }

            ItineraryPrice::query()->updateOrCreate(
                [
                    'itinerary_id' => $itinerary->id,
                    'passenger_type_id' => $typeId,
                ],
                [
                    'price' => $priceRaw,
                    'taxes' => $taxesRaw,
                ]
            );
        }

        return redirect()
            ->route('admin.itineraries.prices.edit', $itinerary)
            ->with('success', 'Tarifas por tipo de pasajero actualizadas.');
    }
}
