@extends('adminlte::page')

@section('title', 'Factura ' . $invoice->number)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>{{ $invoice->isCreditNote() ? 'Abono' : 'Factura' }} {{ $invoice->number }}</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.facturas.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        <a href="{{ route('admin.booking.show', $invoice->booking) }}" class="btn btn-info"><i class="fas fa-calendar-check"></i> Ver reserva</a>
    </div>
</div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice"></i> Datos de la factura</h3>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Número</dt>
                <dd class="col-sm-9"><code>{{ $invoice->number }}</code></dd>

                <dt class="col-sm-3">Tipo</dt>
                <dd class="col-sm-9">
                    @if ($invoice->isCreditNote())
                        Abono
                        @if ($invoice->rectifiedInvoice)
                            de <a href="{{ route('admin.facturas.show', $invoice->rectifiedInvoice) }}">{{ $invoice->rectifiedInvoice->number }}</a>
                        @endif
                    @else
                        Factura
                        @if ($invoice->hasBeenCredited())
                            <span class="badge badge-warning ml-1">abonada</span>
                        @endif
                    @endif
                </dd>

                <dt class="col-sm-3">Reserva</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('admin.booking.show', $invoice->booking) }}">
                        {{ $invoice->booking->external_ref ?? '#'.$invoice->booking_id }}
                    </a>
                </dd>

                <dt class="col-sm-3">Importe</dt>
                <dd class="col-sm-9">{{ number_format((float) $invoice->total_amount, 2, ',', '.') }} {{ $invoice->currency }}</dd>

                <dt class="col-sm-3">Fecha de emisión</dt>
                <dd class="col-sm-9">{{ $invoice->issue_date?->format('d/m/Y') ?? '—' }}</dd>

                <dt class="col-sm-3">Titular</dt>
                <dd class="col-sm-9">{{ $invoice->billing_name ?? '—' }}</dd>

                <dt class="col-sm-3">NIF / VAT</dt>
                <dd class="col-sm-9">{{ $invoice->billing_vat ?? '—' }}</dd>

                <dt class="col-sm-3">Dirección</dt>
                <dd class="col-sm-9">{{ $invoice->billing_address ?? '—' }}</dd>

                <dt class="col-sm-3">Ciudad</dt>
                <dd class="col-sm-9">{{ $invoice->billing_city ?? '—' }}</dd>

                <dt class="col-sm-3">País</dt>
                <dd class="col-sm-9">{{ $invoice->billing_country ?? '—' }}</dd>

                <dt class="col-sm-3">Emitida por</dt>
                <dd class="col-sm-9">{{ $invoice->createdUser?->name ?? '—' }}</dd>
            </dl>
        </div>
    </div>
@stop
