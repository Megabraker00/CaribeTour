@extends('adminlte::page')

@section('title', 'Nuevo proveedor')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Nuevo proveedor</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            @include('admin.supplier.form', ['action' => route('admin.suppliers.store')])
        </div>
    </div>
@stop
