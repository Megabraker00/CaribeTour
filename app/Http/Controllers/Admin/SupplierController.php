<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::query()
            ->with('statusRecord')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('admin.supplier.create', [
            'statuses' => $this->supplierStatuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Supplier::create($this->validatedSupplier($request));

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('admin.supplier.edit', [
            'supplier' => $supplier,
            'statuses' => $this->supplierStatuses(),
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validatedSupplier($request));

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->products()->exists()) {
            return redirect()
                ->route('admin.suppliers.index')
                ->with('error', 'No se puede eliminar el proveedor porque tiene productos asociados.');
        }

        $supplier->delete();

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }

    private function supplierStatuses()
    {
        return Status::query()
            ->where('statusable', Supplier::class)
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedSupplier(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:100',
            'status_id' => [
                'required',
                'integer',
                Rule::exists('statuses', 'id')->where('statusable', Supplier::class),
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'status_id.required' => 'Selecciona un estado.',
        ]);
    }
}
