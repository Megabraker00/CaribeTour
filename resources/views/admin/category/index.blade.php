@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Categorías</h1>
        </div>
        <div class="col-sm text-right">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva categoría
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

    <div class="card card-outline card-primary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 56px">Img</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Categoría padre</th>
                            <th>Estado</th>
                            <th class="text-right" style="width: 1%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $cat)
                            @php
                                $thumb = $cat->images->firstWhere('is_main', true) ?? $cat->images->first();
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    @if ($thumb)
                                        <img src="{{ asset($thumb->path) }}" alt="" class="img-thumbnail p-0" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>{{ $cat->id }}</td>
                                <td>
                                    @if ($cat->parent_id)
                                        <span class="text-muted">└</span>
                                    @endif
                                    {{ $cat->name }}
                                </td>
                                <td><code class="small">{{ $cat->slug }}</code></td>
                                <td>{{ $cat->parentCategory?->name ?? '—' }}</td>
                                <td>{{ $cat->statusRecord?->name ?? $cat->status_id }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary"
                                        title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay categorías.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
