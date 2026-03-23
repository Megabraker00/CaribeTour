<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Employee;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Position;
use App\Models\Product;
use App\Models\Status;
use App\Models\Subscriber;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StatusController extends Controller
{
    /**
     * Modelos permitidos para statusable (FQCN → etiqueta).
     * Deben coincidir con los usos reales de status_id en la BD.
     *
     * @return array<string, string>
     */
    public static function statusableOptions(): array
    {
        return [
            Product::class => 'Producto',
            Booking::class => 'Reserva',
            Client::class => 'Cliente',
            Payment::class => 'Pago',
            Category::class => 'Categoría',
            Supplier::class => 'Proveedor',
            Blog::class => 'Blog',
            Passenger::class => 'Pasajero',
            Employee::class => 'Empleado',
            Comment::class => 'Comentario',
            Position::class => 'Puesto / posición',
            Subscriber::class => 'Suscriptor',
        ];
    }

    public function index(): View
    {
        $statuses = Status::query()->orderBy('statusable')->orderBy('name')->get();

        return view('admin.status.index', [
            'statuses' => $statuses,
            'statusableOptions' => self::statusableOptions(),
        ]);
    }

    public function create(): View
    {
        return view('admin.status.create', [
            'statusableOptions' => self::statusableOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $allowed = array_keys(self::statusableOptions());

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'statusable' => ['required', 'string', Rule::in($allowed)],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'statusable.required' => 'Selecciona el modelo al que aplica el estado.',
            'statusable.in' => 'El modelo seleccionado no es válido.',
        ]);

        Status::create($validated);

        return redirect()
            ->route('admin.statuses.index')
            ->with('success', 'Estado creado correctamente.');
    }

    public function edit(Status $status): View
    {
        return view('admin.status.edit', [
            'status' => $status,
            'statusableOptions' => self::statusableOptions(),
        ]);
    }

    public function update(Request $request, Status $status): RedirectResponse
    {
        $allowed = array_keys(self::statusableOptions());
        if (! in_array($status->statusable, $allowed, true)) {
            $allowed[] = $status->statusable;
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'statusable' => ['required', 'string', Rule::in($allowed)],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'statusable.required' => 'Selecciona el modelo al que aplica el estado.',
            'statusable.in' => 'El modelo seleccionado no es válido.',
        ]);

        if ($validated['statusable'] !== $status->statusable && $this->statusHasReferences($status)) {
            return redirect()
                ->route('admin.statuses.edit', $status)
                ->withInput()
                ->withErrors([
                    'statusable' => 'No se puede cambiar el modelo porque este estado ya está en uso.',
                ]);
        }

        $status->update($validated);

        return redirect()
            ->route('admin.statuses.index')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(Status $status): RedirectResponse
    {
        if ($this->statusHasReferences($status)) {
            return redirect()
                ->route('admin.statuses.index')
                ->with('error', 'No se puede eliminar el estado porque está asignado a registros existentes.');
        }

        $status->delete();

        return redirect()
            ->route('admin.statuses.index')
            ->with('success', 'Estado eliminado correctamente.');
    }

    private function statusHasReferences(Status $status): bool
    {
        $id = $status->id;

        return Product::where('status_id', $id)->exists()
            || Booking::where('status_id', $id)->exists()
            || Client::where('status_id', $id)->exists()
            || Payment::where('status_id', $id)->exists()
            || Category::where('status_id', $id)->exists()
            || Supplier::where('status_id', $id)->exists()
            || Blog::where('status_id', $id)->exists()
            || Passenger::where('status_id', $id)->exists()
            || Employee::where('status_id', $id)->exists()
            || Comment::where('status_id', $id)->exists()
            || Position::where('status_id', $id)->exists()
            || Subscriber::where('status_id', $id)->exists();
    }
}
