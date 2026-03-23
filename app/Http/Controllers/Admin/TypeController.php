<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TypeController extends Controller
{
    /**
     * Modelos permitidos para el campo polimórfico typeable (valor = FQCN).
     *
     * @return array<string, string>
     */
    public static function typeableOptions(): array
    {
        return [
            Product::class => 'Producto',
            Payment::class => 'Pago',
            Passenger::class => 'Pasajero',
        ];
    }

    public function index(): View
    {
        $types = Type::query()->orderBy('typeable')->orderBy('name')->get();

        return view('admin.type.index', [
            'types' => $types,
            'typeableOptions' => self::typeableOptions(),
        ]);
    }

    public function create(): View
    {
        return view('admin.type.create', [
            'typeableOptions' => self::typeableOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $allowed = array_keys(self::typeableOptions());

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'typeable' => ['required', 'string', Rule::in($allowed)],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'typeable.required' => 'Selecciona el modelo al que aplica el tipo.',
            'typeable.in' => 'El modelo seleccionado no es válido.',
        ]);

        Type::create($validated);

        return redirect()
            ->route('admin.types.index')
            ->with('success', 'Tipo creado correctamente.');
    }

    public function edit(Type $type): View
    {
        return view('admin.type.edit', [
            'type' => $type,
            'typeableOptions' => self::typeableOptions(),
        ]);
    }

    public function update(Request $request, Type $type): RedirectResponse
    {
        $allowed = array_keys(self::typeableOptions());
        if (! in_array($type->typeable, $allowed, true)) {
            $allowed[] = $type->typeable;
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'typeable' => ['required', 'string', Rule::in($allowed)],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'typeable.required' => 'Selecciona el modelo al que aplica el tipo.',
            'typeable.in' => 'El modelo seleccionado no es válido.',
        ]);

        if ($validated['typeable'] !== $type->typeable && $this->typeHasReferences($type)) {
            return redirect()
                ->route('admin.types.edit', $type)
                ->withInput()
                ->withErrors([
                    'typeable' => 'No se puede cambiar el modelo porque este tipo ya está en uso.',
                ]);
        }

        $type->update($validated);

        return redirect()
            ->route('admin.types.index')
            ->with('success', 'Tipo actualizado correctamente.');
    }

    public function destroy(Type $type): RedirectResponse
    {
        if ($this->typeHasReferences($type)) {
            return redirect()
                ->route('admin.types.index')
                ->with('error', 'No se puede eliminar el tipo porque está asignado a productos, segmentos, pagos o pasajeros.');
        }

        $type->delete();

        return redirect()
            ->route('admin.types.index')
            ->with('success', 'Tipo eliminado correctamente.');
    }

    private function typeHasReferences(Type $type): bool
    {
        $id = $type->id;

        if (Product::where('type_id', $id)->exists()) {
            return true;
        }

        if (Segment::where('type_id', $id)->exists()) {
            return true;
        }

        if (Payment::where('type_id', $id)->exists()) {
            return true;
        }

        if (Passenger::where('passenger_type_id', $id)->exists()) {
            return true;
        }

        if (DB::table('itinerary_prices')->where('passenger_type_id', $id)->exists()) {
            return true;
        }

        return false;
    }
}
