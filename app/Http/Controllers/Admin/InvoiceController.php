<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Services\InvoiceService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoices)
    {
    }

    public function index(): View
    {
        return view('admin.facturas.index');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'booking.client',
            'booking.statusRecord',
            'createdUser',
            'rectifiedInvoice',
            'creditNotes',
        ]);

        return view('admin.facturas.show', compact('invoice'));
    }

    public function store(Booking $booking): RedirectResponse
    {
        try {
            $invoice = $this->invoices->issueForBooking($booking, auth()->id());
        } catch (DomainException $exception) {
            return redirect()
                ->route('admin.booking.show', $booking)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.booking.show', $booking)
            ->with('success', 'Factura '.$invoice->number.' emitida correctamente.');
    }

    public function storeCredit(Booking $booking, Invoice $invoice): RedirectResponse
    {
        try {
            $credit = $this->invoices->issueCreditNote($booking, $invoice, auth()->id());
        } catch (DomainException $exception) {
            return redirect()
                ->route('admin.booking.show', $booking)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.booking.show', $booking)
            ->with('success', 'Abono '.$credit->number.' emitido correctamente.');
    }
}
