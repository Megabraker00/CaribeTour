<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['booking', 'createdUser'])->get();

        return view('admin.facturas.index', compact('invoices'));
    }

    public function create()
    {
        return view('admin.facturas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'total' => 'required|numeric',
            'status' => 'required|string|in:pending,paid,cancelled',
            'booking_id' => 'required|exists:bookings,id',
            'created_user_id' => 'required|exists:users,id',
        ]);

        Invoice::create($validated);

        return redirect()->route('admin.facturas.index')->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invoice)
    {
        return view('admin.facturas.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'total' => 'required|numeric',
            'status' => 'required|string|in:pending,paid,cancelled',
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.facturas.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('admin.facturas.index')->with('success', 'Invoice deleted successfully.');
    }
}