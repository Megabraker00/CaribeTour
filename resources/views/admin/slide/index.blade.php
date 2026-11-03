@extends('adminlte::page')

@section('title', 'Diapositivas')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Diapositivas</h1>
        </div>
        <div class="col-sm text-right">
            @can('write-admin')
                <a href="{{ route('admin.slides.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva diapositiva
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
                            <th>Orden</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Enlace</th>
                            <th>Estado</th>
                            <th class="text-right" style="width: 1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                            <tr>
                                <td>{{ $slide->sort_order }}</td>
                                <td>
                                    <img src="{{ asset($slide->image_path) }}" alt="" width="72" height="40" style="object-fit: cover;">
                                </td>
                                <td>
                                    {{ $slide->title }}
                                    @if ($slide->text)
                                        <div class="text-muted small">{{ $slide->text }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($slide->product)
                                        {{ $slide->product->name }}
                                    @else
                                        {{ $slide->link_url ?: '—' }}
                                    @endif
                                </td>
                                <td>{{ $slide->scheduleLabel() }}</td>
                                <td class="text-right text-nowrap">
                                    @can('write-admin')
                                        <div class="btn-group" role="group">
                                            <a role="button" href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-sm btn-warning" title="Editar registro">Editar</a>
                                            <button type="submit" class="btn btn-sm btn-danger" form="delete-slide-{{ $slide->id }}">Eliminar</button>
                                        </div>
                                        <form id="delete-slide-{{ $slide->id }}" action="{{ route('admin.slides.destroy', $slide) }}" method="POST" class="d-none" onsubmit="return confirm('¿Seguro de que deseas eliminar este registro?');">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay diapositivas. La portada no muestra el carrusel hasta que añadas una.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
