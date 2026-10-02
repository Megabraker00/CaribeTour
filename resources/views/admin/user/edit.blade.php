@extends('adminlte::page')

@section('title', 'Editar usuario')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Editar usuario</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            @include('admin.user.form', ['action' => route('admin.users.update', $user), 'user' => $user])
        </div>
    </div>
@stop
