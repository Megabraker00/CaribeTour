@extends('adminlte::page')

@section('title', 'Mensaje de ' . $contactMessage->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Mensaje de contacto</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
            @can('write-admin')
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar este mensaje?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i> Eliminar</button>
                </form>
            @endcan
        </div>
    </div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-envelope"></i> Datos del mensaje</h3>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Fecha</dt>
                <dd class="col-sm-9">{{ $contactMessage->created_at?->timezone('Europe/Madrid')->format('d/m/Y H:i') ?? '—' }}</dd>

                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $contactMessage->name }}</dd>

                <dt class="col-sm-3">Correo</dt>
                <dd class="col-sm-9"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></dd>

                <dt class="col-sm-3">Mensaje</dt>
                <dd class="col-sm-9">{!! nl2br(e($contactMessage->message)) !!}</dd>
            </dl>
        </div>
    </div>
@stop
