@extends('adminlte::page')

@section('title', 'Cliente: ' . $client->name . ' ' . $client->last_name)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Detalle del cliente</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.client.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        <a href="{{ route('admin.client.edit', $client) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> Editar</a>
    </div>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user"></i> Datos del cliente</h3>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Nombre</dt>
            <dd class="col-sm-9">{{ $client->name }}</dd>

            <dt class="col-sm-3">Apellidos</dt>
            <dd class="col-sm-9">{{ $client->last_name }}</dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9">{{ $client->email ?? '—' }}</dd>

            <dt class="col-sm-3">Teléfono</dt>
            <dd class="col-sm-9">{{ $client->phone ?? '—' }}</dd>

            <dt class="col-sm-3">DNI / Pasaporte</dt>
            <dd class="col-sm-9">{{ $client->dni_passport }}</dd>

            <dt class="col-sm-3">Fecha de nacimiento</dt>
            <dd class="col-sm-9">{{ $client->date_of_birth ? $client->date_of_birth->format('d/m/Y') : '—' }}</dd>

            <dt class="col-sm-3">Nacionalidad</dt>
            <dd class="col-sm-9">{{ $client->nationality ?? '—' }}</dd>

            <dt class="col-sm-3">Estado</dt>
            <dd class="col-sm-9">
                @if($client->statusRecord)
                    <span class="badge badge-secondary">{{ $client->statusRecord->name }}</span>
                @else
                    {{ $client->status_id ?? '—' }}
                @endif
            </dd>
        </dl>
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-check"></i> Reservas</h3>
    </div>
    <div class="card-body p-0">
        @if($client->bookings->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ref. externa</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->external_ref ?? '—' }}</td>
                        <td>{{ number_format((float) $booking->total_price, 2, ',', '.') }} {{ $booking->currency ?? 'EUR' }}</td>
                        <td>
                            @if($booking->statusRecord)
                                <span class="badge badge-secondary">{{ $booking->statusRecord->name }}</span>
                            @else
                                {{ $booking->status_id }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.booking.show', $booking) }}" class="btn btn-sm btn-info" title="Ver reserva"><i class="fas fa-info"></i> Ver reserva</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="p-3 text-muted mb-0">Este cliente no tiene reservas.</p>
        @endif
    </div>
</div>
@stop
