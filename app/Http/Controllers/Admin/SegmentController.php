<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Segment;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SegmentController extends Controller
{
    /**
     * Crea una nueva fecha de salida: itinerario (precio/stock) + primer segmento (fechas y terminales).
     */
    public function storeTourDate(Request $request)
    {
        $validated = $request->validate([
            'departure_date' => 'required|date',
            'departure_terminal_id' => 'required|integer|exists:terminals,id',
            'arrival_date' => 'required|date|after:departure_date',
            'arrival_terminal_id' => 'required|integer|exists:terminals,id',
            'price' => 'required|numeric|min:0',
            'taxes' => 'required|numeric|min:0',
            'product_id' => 'required|integer|exists:products,id',
        ], [
            'product_id.required' => 'El producto es obligatorio.',
            'departure_terminal_id.exists' => 'Selecciona un terminal de salida válido.',
            'arrival_terminal_id.exists' => 'Selecciona un terminal de llegada válido.',
            'arrival_date.after' => 'La fecha de llegada debe ser posterior a la de salida.',
        ]);

        $productId = (int) $validated['product_id'];

        DB::transaction(function () use ($validated, $productId) {
            $itinerary = Itinerary::create([
                'product_id' => $productId,
                'total_stock' => 20,
                'available_stock' => 20,
                'price' => $validated['price'],
                'taxes' => $validated['taxes'],
                'currency' => 'EUR',
            ]);

            Segment::create([
                'itinerary_id' => $itinerary->id,
                'type_id' => Type::FLIGHT,
                'sort_order' => 1,
                'departure_date' => $validated['departure_date'],
                'departure_terminal_id' => $validated['departure_terminal_id'],
                'arrival_date' => $validated['arrival_date'],
                'arrival_terminal_id' => $validated['arrival_terminal_id'],
                'origin' => null,
                'destination' => null,
            ]);
        });

        return redirect()->to(route('admin.tour.show', $productId).'#tour-itineraries')
            ->with('success', 'Fecha de salida añadida correctamente.');
    }
}
