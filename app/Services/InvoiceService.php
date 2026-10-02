<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use DomainException;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function issueForBooking(Booking $booking, ?int $userId = null): Invoice
    {
        return DB::transaction(function () use ($booking, $userId) {
            $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            $booking->load(['client', 'invoices.creditNotes']);

            if (!$booking->canIssueInvoice()) {
                throw new DomainException('Esta reserva no admite una factura nueva.');
            }

            $amount = round((float) $booking->total_price, 2);
            if ($amount <= 0) {
                throw new DomainException('El importe de la reserva debe ser mayor que cero.');
            }

            $client = $booking->client;

            return Invoice::create([
                'number' => $this->nextNumber(),
                'booking_id' => $booking->id,
                'rectifies_invoice_id' => null,
                'created_user_id' => $userId,
                'total_amount' => $amount,
                'currency' => $booking->currency ?: 'EUR',
                'billing_name' => $this->billingName($booking),
                'billing_vat' => $client?->dni_passport,
                'billing_address' => null,
                'billing_city' => null,
                'billing_country' => $this->billingCountry($client?->nationality),
                'issue_date' => now()->toDateString(),
            ]);
        });
    }

    public function issueCreditNote(Booking $booking, Invoice $invoice, ?int $userId = null): Invoice
    {
        return DB::transaction(function () use ($booking, $invoice, $userId) {
            $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);
            $invoice = Invoice::query()->lockForUpdate()->findOrFail($invoice->id);
            $booking->load(['invoices.creditNotes']);
            $invoice->load('creditNotes');

            if ((int) $invoice->booking_id !== (int) $booking->id) {
                throw new DomainException('La factura no pertenece a esta reserva.');
            }

            if (!$invoice->isPositive() || $invoice->hasBeenCredited()) {
                throw new DomainException('Esta factura no admite un abono.');
            }

            if (!$booking->canIssueCreditNote()) {
                throw new DomainException('Solo puedes emitir un abono si la reserva está cancelada o reembolsada.');
            }

            return Invoice::create([
                'number' => $this->nextNumber(),
                'booking_id' => $booking->id,
                'rectifies_invoice_id' => $invoice->id,
                'created_user_id' => $userId,
                'total_amount' => round(-1 * (float) $invoice->total_amount, 2),
                'currency' => $invoice->currency ?: 'EUR',
                'billing_name' => $invoice->billing_name,
                'billing_vat' => $invoice->billing_vat,
                'billing_address' => $invoice->billing_address,
                'billing_city' => $invoice->billing_city,
                'billing_country' => $invoice->billing_country,
                'issue_date' => now()->toDateString(),
            ]);
        });
    }

    private function nextNumber(): string
    {
        $prefix = 'FAC-'.now()->year.'-';
        $last = Invoice::query()
            ->where('number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('number')
            ->value('number');

        $sequence = $last ? ((int) substr((string) $last, -4) + 1) : 1;

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    private function billingName(Booking $booking): string
    {
        $client = $booking->client;
        $name = trim(($client?->name ?? '').' '.($client?->last_name ?? ''));

        return $name !== '' ? $name : ('Reserva '.$booking->external_ref);
    }

    private function billingCountry(?string $nationality): ?string
    {
        if ($nationality === null) {
            return null;
        }

        $code = strtoupper(trim($nationality));

        return preg_match('/^[A-Z]{2}$/', $code) ? $code : null;
    }
}
