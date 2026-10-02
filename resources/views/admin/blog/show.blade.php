@extends('adminlte::page')

@section('title', 'Post: ' . $blog->name)

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Detalle del post</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Editar</a>
    </div>
</div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-newspaper"></i> Datos del post</h3>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Título</dt>
                <dd class="col-sm-9">{{ $blog->name }}</dd>

                <dt class="col-sm-3">Slug</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" rel="noopener noreferrer">
                        <code>{{ $blog->slug }}</code>
                        <i class="fas fa-external-link-alt fa-xs ml-1" aria-hidden="true"></i>
                    </a>
                </dd>

                <dt class="col-sm-3">Estado</dt>
                <dd class="col-sm-9">
                    @if ($blog->statusRecord)
                        <span class="badge badge-secondary">{{ $blog->statusRecord->name }}</span>
                    @else
                        {{ $blog->status_id }}
                    @endif
                </dd>

                <dt class="col-sm-3">Imagen principal</dt>
                <dd class="col-sm-9">
                    @php $blogMainImage = $blog->mainImage(); @endphp
                    @if ($blogMainImage)
                        <div class="border rounded p-2 bg-light" style="max-width: 320px;">
                            <img src="{{ asset($blogMainImage->path) }}" alt="{{ $blog->mainImageAlt() }}" class="img-fluid rounded">
                            @if (filled($blogMainImage->alt))
                                <div class="small text-muted mt-1"><code>alt</code>: {{ $blogMainImage->alt }}</div>
                            @endif
                        </div>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-3">Autor</dt>
                <dd class="col-sm-9">{{ $blog->createdUser?->name ?? '—' }}</dd>

                <dt class="col-sm-3">Redes del autor</dt>
                <dd class="col-sm-9">
                    @forelse ($blog->authorSocialLinks() as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="mr-3">
                            <i class="{{ $link['icon'] }}" style="{{ $link['style'] }}"></i>
                            {{ $link['label'] }}
                        </a>
                    @empty
                        —
                    @endforelse
                </dd>

                <dt class="col-sm-3">Creado</dt>
                <dd class="col-sm-9">{{ $blog->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>

                <dt class="col-sm-3">Actualizado</dt>
                <dd class="col-sm-9">{{ $blog->updated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-align-left"></i> Contenido</h3>
        </div>
        <div class="card-body">
            {!! $blog->content !!}
        </div>
    </div>
@stop
