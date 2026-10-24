@extends('front_template')

@section('title', 'Blog de viajes al Caribe')
@section('description', 'Ideas, guías y noticias para preparar un viaje al Caribe con CaribeTour.')


@section('content')
    <section class="container my-4">

        <h1 class="mb-4">Blog de viajes al Caribe</h1>

        <div class="row">
            <div class="col-md-8">
                @foreach ($blogs as $index => $blog ) 
                <article>
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none">
                                <img src="{{ asset($blog->mainImage()?->path ?? 'images/i-love-bootstrap2.png') }}" width="100%" class="mb-4" alt="{{ $blog->mainImageAlt() }}" loading="lazy" decoding="async">
                            </a>

                            <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none">
                                <h2 class="h4 card-title">
                                    {{ $blog->name }}
                                </h2>
                            </a>

                            <p class="card-text">{!! $blog->excerptHtml(500) !!}</p>
                            @if ($blog->created_at)
                                <p><strong>Publicado:</strong> <time datetime="{{ $blog->created_at->toDateString() }}">{{ $blog->created_at->format('d-m-Y') }}</time></p>
                            @endif
                            
                            @include('components.blog-author', ['blog' => $blog])
                        </div>
                        <div class="card-footer">
                            <i class="bi bi-chat-right-text-fill"></i> Comentarios 
                            <span class="badge rounded-pill bg-secondary ms-2">75</span>
                            <i class="bi bi-eye-fill"></i>3.654

                            <div title="Etiquetas">
                                <span class="badge bg-primary">Primary</span>
                                <span class="badge bg-secondary">Secondary</span>
                                <span class="badge bg-success">Success</span>
                                <span class="badge bg-danger">Danger</span>
                                <span class="badge bg-warning text-dark">Warning</span>
                                <span class="badge bg-info text-dark">Info</span>
                                <span class="badge bg-dark">Dark</span>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach

            </div>

            <div class="col-md-4">
                @include('components.promo')
            </div>
        </div>

    </section>
@endsection