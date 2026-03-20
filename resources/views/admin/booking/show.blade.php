@extends('admin.form_template')

@section('title', 'Detalle de reserva #' . $booking->id)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Reserva #{{ $booking->id }}</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.booking.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">

    

    
    <div class="row">

        <div class="col-md-6">
        {{-- Cliente titular --}}
            <div class="card card-outline card-primary">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h3 class="card-title mb-0"><i class="fas fa-user"></i> Cliente titular</h3>
                    @if($booking->client)
                        <a href="{{ route('admin.client.show', $booking->client) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt"></i> Ver ficha del cliente
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($booking->client)
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Nombre</dt>
                        <dd class="col-sm-9">{{ $booking->client->name }} {{ $booking->client->last_name }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">
                            @if (filled($booking->client->email))
                                <a href="mailto:{{ $booking->client->email }}">{{ $booking->client->email }}</a>
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-sm-3">Teléfono</dt>
                        <dd class="col-sm-9">{{ $booking->client->phone ?? '—' }}</dd>
                        <dt class="col-sm-3">DNI / Pasaporte</dt>
                        <dd class="col-sm-9">{{ $booking->client->dni_passport ?? '—' }}</dd>
                        <dt class="col-sm-3">Nacionalidad</dt>
                        <dd class="col-sm-9">{{ $booking->client->nationality ?? '—' }}</dd>
                    </dl>
                    @else
                    <p class="text-muted mb-0">Sin cliente titular asignado.</p>
                    @endif
                </div>
            </div>
        </div>



        {{-- Resumen reserva --}}
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Resumen</h3>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Ref. externa:</strong> {{ $booking->external_ref ?? '—' }}</p>
                    <p class="mb-1"><strong>Total:</strong> {{ number_format((float) $booking->total_price, 2, ',', '.') }} {{ $booking->currency ?? 'EUR' }}</p>
                    <p class="mb-0"><strong>Estado:</strong> {{ $booking->statusRecord->name ?? $booking->status_id }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Notas de la reserva --}}
    @if(filled($booking->notes))
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-sticky-note"></i> Notas de la reserva</h3>
                </div>
                <div class="card-body">
                    <div class="text-break">{{ $booking->notes }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Pagos --}}
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-credit-card"></i> Pagos</h3>
        </div>
        <div class="card-body p-0">
            @if($booking->payments->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Importe</th>
                            <th>Moneda</th>
                            <th>Tipo</th>
                            <th>Método</th>
                            <th>Transacción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ number_format((float) $payment->amount, 2, ',', '.') }}</td>
                            <td>{{ $payment->currency ?? 'EUR' }}</td>
                            <td>{{ $payment->type->name ?? '—' }}</td>
                            <td>{{ $payment->payment_method ?? '—' }}</td>
                            <td>{{ $payment->transaction_id ?? '—' }}</td>
                            <td><span class="badge badge-secondary">{{ $payment->statusRecord ?? $payment->status_id }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="p-3 text-muted mb-0">No hay pagos registrados para esta reserva.</p>
            @endif
        </div>
    </div>

    {{-- Pasajeros --}}
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Pasajeros</h3>
        </div>
        <div class="card-body p-0">
            @if($booking->passengers->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>DNI/Pasaporte</th>
                            <th>Nacimiento</th>
                            <th>Nacionalidad</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Tasas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->passengers as $pax)
                        <tr>
                            <td>{{ $pax->name }}</td>
                            <td>{{ $pax->last_name }}</td>
                            <td>{{ $pax->dni_passport ?? '—' }}</td>
                            <td>{{ $pax->date_of_birth ? \Carbon\Carbon::parse($pax->date_of_birth)->format('d/m/Y') : '—' }}</td>
                            <td>{{ $pax->nationality ?? '—' }}</td>
                            <td>{{ $pax->type->name ?? '—' }}</td>
                            <td>{{ $pax->price_at_booking ? number_format((float) $pax->price_at_booking, 2, ',', '.') : '—' }}</td>
                            <td>{{ $pax->taxes_at_booking ? number_format((float) $pax->taxes_at_booking, 2, ',', '.') : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="p-3 text-muted mb-0">No hay pasajeros en esta reserva.</p>
            @endif
        </div>
    </div>

    {{-- Itinerarios con segmentos (en orden) --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-route"></i> Itinerarios</h3>
        </div>
        <div class="card-body">
            @if($booking->itineraries->isNotEmpty())
                @foreach($booking->itineraries as $orden => $itinerary)
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">
                        Itinerario {{ $orden + 1 }}: {{ $itinerary->product->name ?? 'Producto' }}
                        @if($itinerary->departure_t && $itinerary->arrival_t)
                            <small class="text-muted">— Desde {{ $itinerary->departure_t->name }} hasta {{ $itinerary->arrival_t->name }}</small>
                        @endif
                        <span class="badge badge-info">{{ $itinerary->days }} días / {{ $itinerary->nights }} noches</span>
                    </h5>
                    <p class="text-muted small mb-2">Precio: {{ number_format((float) $itinerary->price, 2, ',', '.') }} {{ $itinerary->currency ?? 'EUR' }} + tasas {{ number_format((float) $itinerary->taxes, 2, ',', '.') }}</p>

                    @if($itinerary->segments->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Tipo</th>
                                    <th>Salida</th>
                                    <th>Origen</th>
                                    <th>Llegada</th>
                                    <th>Destino</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itinerary->segments as $seg)
                                <tr>
                                    <td>{{ $seg->sort_order }}</td>
                                    <td>{{ $seg->type->name ?? '—' }}</td>
                                    <td>
                                        {{ $seg->departure_date ? $seg->departure_date->format('d/m/Y H:i') : '—' }}
                                        @if($seg->departureTerminal)
                                            <br><small class="text-muted">{{ $seg->departureTerminal->name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $seg->origin ?? '—' }}</td>
                                    <td>
                                        {{ $seg->arrival_date ? $seg->arrival_date->format('d/m/Y H:i') : '—' }}
                                        @if($seg->arrivalTerminal)
                                            <br><small class="text-muted">{{ $seg->arrivalTerminal->name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $seg->destination ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted small mb-0">Sin segmentos definidos.</p>
                    @endif
                </div>
                @endforeach
            @else
            <p class="text-muted mb-0">No hay itinerarios asociados a esta reserva.</p>
            @endif
        </div>
    </div>

</div>
@endsection
