@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Proveedores</h1>
        </div>
        <div class="col-sm text-right">
            @can('write-admin')
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo proveedor
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
                            <th>Estado</th>
                            <th>Productos</th>
                            <th class="text-right" style="width: 1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->statusRecord?->name ?? '—' }}</td>
                                <td>{{ $supplier->products_count }}</td>
                                <td class="text-right text-nowrap">
                                    @can('write-admin')
                                        <div class="btn-group" role="group">
                                            <a role="button" href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning" title="Editar registro">Editar</a>
                                            <button type="submit" class="btn btn-sm btn-danger" form="delete-supplier-{{ $supplier->id }}" title="Eliminar">Eliminar</button>
                                        </div>
                                        <form id="delete-supplier-{{ $supplier->id }}" action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="d-none"
                                            onsubmit="return confirm('¿Eliminar este proveedor?');">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay proveedores registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
