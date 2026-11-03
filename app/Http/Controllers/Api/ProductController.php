<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="CaribeTour API",
 *     version="1.0.0",
 *     description="Salidas que el calendario de un tour consulta al cambiar de mes."
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}/itineraries",
     *     summary="Salidas con plazas de un producto público",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Salidas del mes, o todas si no se indica mes y año"),
     *     @OA\Response(response=404, description="El producto no es público")
     * )
     */
    public function tourItineraries(Request $request, Product $product)
    {
        abort_unless($product->isPubliclyVisible(), 404);

        $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);

        $data = $product->itineraries()
            ->where('available_stock', '>', 0)
            ->withBookableDeparture()
            ->with(['segments' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->get()
            ->map(function (Itinerary $itinerary) use ($request) {
                $segment = $itinerary->openingSegment();

                if (!$segment?->departure_date) {
                    return null;
                }

                if ($request->filled(['month', 'year']) && (
                    (int) $segment->departure_date->year !== (int) $request->year
                    || (int) $segment->departure_date->month !== (int) $request->month
                )) {
                    return null;
                }

                return [
                    'id' => $itinerary->id,
                    'departure_date' => $segment->departure_date->toDateString(),
                    'price' => $itinerary->price,
                    'taxes' => $itinerary->taxes,
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'product_id' => $product->id,
            'currency' => 'EUR',
            'data' => $data,
        ]);
    }
}
