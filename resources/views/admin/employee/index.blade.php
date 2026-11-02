@extends('adminlte::page')

@section('title', 'Empleados')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Empleados</h1>
        </div>
        <div class="col-sm text-right">
            @can('write-admin')
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo empleado
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
                            <th>Email empresa</th>
                            <th>Cargo</th>
                            <th>Estado</th>
                            <th class="text-right" style="width: 1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }} {{ $employee->last_name }}</td>
                                <td>{{ $employee->email_company }}</td>
                                <td>{{ $employee->position?->name ?? '—' }}</td>
                                <td>{{ $employee->statusRecord?->name ?? '—' }}</td>
                                <td class="text-right text-nowrap">
                                    @can('write-admin')
                                        <div class="btn-group" role="group">
                                            <a role="button" href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-warning" title="Editar registro">Editar</a>
                                            <button type="submit" class="btn btn-sm btn-danger" form="delete-employee-{{ $employee->id }}" title="Eliminar">Eliminar</button>
                                        </div>
                                        <form id="delete-employee-{{ $employee->id }}" action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="d-none"
                                            onsubmit="return confirm('¿Eliminar este empleado?');">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay empleados registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
