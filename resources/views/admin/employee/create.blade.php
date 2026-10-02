@extends('adminlte::page')

@section('title', 'Nuevo empleado')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Nuevo empleado</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            @include('admin.employee.form', ['action' => route('admin.employees.store')])
        </div>
    </div>
@stop
