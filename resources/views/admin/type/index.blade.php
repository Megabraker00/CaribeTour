@extends('adminlte::page')

@section('title', 'Tipos')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Tipos</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.types.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo tipo
            </a>
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Modelo (typeable)</th>
                            <th class="text-right" style="width: 1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($types as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->name }}</td>
                                <td>
                                    <code class="small">{{ $t->typeable }}</code>
                                    @if (isset($typeableOptions[$t->typeable]))
                                        <span class="text-muted">— {{ $typeableOptions[$t->typeable] }}</span>
                                    @endif
                                </td>
                                <td class="text-right text-nowrap">
                                    <a href="{{ route('admin.types.edit', $t) }}" class="btn btn-sm btn-outline-primary"
                                        title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('admin.types.destroy', $t) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Eliminar este tipo? Solo es posible si no está en uso.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay tipos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
