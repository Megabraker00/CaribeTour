<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Terminal;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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

    public function manageItinerarySegments(Itinerary $itinerary): View
    {
        $itinerary->load([
            'product',
            'segments' => function ($q) {
                $q->orderBy('sort_order')->with(['type', 'departureTerminal', 'arrivalTerminal']);
            },
        ]);

        $terminals = Terminal::query()->orderBy('name')->get();
        $segmentTypes = Type::query()->where('typeable', Product::class)->orderBy('name')->get();

        $nextSortOrder = (int) $itinerary->segments()->max('sort_order') + 1;
        if ($nextSortOrder < 1) {
            $nextSortOrder = 1;
        }

        return view('admin.itinerary.segments', [
            'itinerary' => $itinerary,
            'terminals' => $terminals,
            'segmentTypes' => $segmentTypes,
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    public function storeSegment(Request $request, Itinerary $itinerary): RedirectResponse
    {
        $validated = $this->validatedSegment($request);

        $sortOrder = (int) $validated['sort_order'];
        if ($sortOrder < 1) {
            $sortOrder = 1;
        }

        Segment::create([
            'itinerary_id' => $itinerary->id,
            'type_id' => $validated['type_id'],
            'sort_order' => $sortOrder,
            'departure_date' => $validated['departure_date'],
            'departure_terminal_id' => $validated['departure_terminal_id'],
            'arrival_date' => $validated['arrival_date'],
            'arrival_terminal_id' => $validated['arrival_terminal_id'],
            'origin' => $validated['origin'] ?? null,
            'destination' => $validated['destination'] ?? null,
        ]);

        return redirect()
            ->route('admin.itineraries.segments.index', $itinerary)
            ->with('success', 'Segmento añadido correctamente.');
    }

    public function editSegment(Itinerary $itinerary, Segment $segment): View
    {
        $segment = $this->segmentBelongsToItinerary($itinerary, $segment);

        $itinerary->load('product');
        $segment->load(['type', 'departureTerminal', 'arrivalTerminal']);

        $terminals = Terminal::query()->orderBy('name')->get();
        $segmentTypes = Type::query()->where('typeable', Product::class)->orderBy('name')->get();

        return view('admin.itinerary.segments_edit', [
            'itinerary' => $itinerary,
            'segment' => $segment,
            'terminals' => $terminals,
            'segmentTypes' => $segmentTypes,
        ]);
    }

    public function updateSegment(Request $request, Itinerary $itinerary, Segment $segment): RedirectResponse
    {
        $segment = $this->segmentBelongsToItinerary($itinerary, $segment);

        $validated = $this->validatedSegment($request);

        $sortOrder = (int) $validated['sort_order'];
        if ($sortOrder < 1) {
            $sortOrder = 1;
        }

        $segment->update([
            'type_id' => $validated['type_id'],
            'sort_order' => $sortOrder,
            'departure_date' => $validated['departure_date'],
            'departure_terminal_id' => $validated['departure_terminal_id'],
            'arrival_date' => $validated['arrival_date'],
            'arrival_terminal_id' => $validated['arrival_terminal_id'],
            'origin' => $validated['origin'] ?? null,
            'destination' => $validated['destination'] ?? null,
        ]);

        return redirect()
            ->route('admin.itineraries.segments.index', $itinerary)
            ->with('success', 'Segmento actualizado correctamente.');
    }

    public function destroySegment(Itinerary $itinerary, Segment $segment): RedirectResponse
    {
        $segment = $this->segmentBelongsToItinerary($itinerary, $segment);

        if ($itinerary->segments()->count() <= 1) {
            return redirect()
                ->route('admin.itineraries.segments.index', $itinerary)
                ->with('error', 'No puedes eliminar el único segmento del itinerario. Añade otro antes o elimina la fecha de salida completa desde el listado del tour.');
        }

        $segment->delete();

        return redirect()
            ->route('admin.itineraries.segments.index', $itinerary)
            ->with('success', 'Segmento eliminado.');
    }

    private function segmentBelongsToItinerary(Itinerary $itinerary, Segment $segment): Segment
    {
        if ((int) $segment->itinerary_id !== (int) $itinerary->id) {
            abort(404);
        }

        return $segment;
    }

    /**
     * Validación del formulario “Añadir segmento”.
     *
     * @return array<string, mixed>
     */
    private function validatedSegment(Request $request): array
    {
        $typeRule = Rule::exists('types', 'id')->where('typeable', Product::class);

        return $request->validate([
            'type_id' => ['required', 'integer', $typeRule],
            'sort_order' => 'required|integer|min:1',
            'departure_date' => 'required|date',
            'departure_terminal_id' => 'required|integer|exists:terminals,id',
            'arrival_date' => 'required|date|after:departure_date',
            'arrival_terminal_id' => 'required|integer|exists:terminals,id',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
        ], [
            'type_id.required' => 'Selecciona el tipo de segmento.',
            'arrival_date.after' => 'La fecha de llegada debe ser posterior a la de salida.',
        ]);
    }
}
