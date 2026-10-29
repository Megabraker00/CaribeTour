@extends('adminlte::page')

@section('title', 'Terminales')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Terminales</h1>
        </div>
        <div class="col-sm text-right">
            @can('write-admin')
                <a href="{{ route('admin.terminals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo terminal
                </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card card-outline card-primary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Terminal padre</th>
                            <th class="text-right" style="width: 1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($terminals as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->name }}</td>
                                <td>{{ $t->address ?: '—' }}</td>
                                <td>{{ $t->parentTerminal?->name ?? '—' }}</td>
                                <td class="text-right text-nowrap">
                                    @can('write-admin')
                                        <a href="{{ route('admin.terminals.edit', $t) }}" class="btn btn-sm btn-warning"
                                            title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay terminales registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
