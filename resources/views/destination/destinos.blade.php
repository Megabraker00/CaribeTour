@extends('front_template')
@section('title', 'Destinos del Caribe')
@section('description', 'Elige un país del Caribe y compara tours con salida, plazas y precio por persona.')

@section('content')
    <!-- container -->
    <section class="container my-4">

        <h1 class="mb-4">Destinos del Caribe</h1>

        <div class="row">

            @forelse($categories as $category)
            @php
                $countryImg = $category->images->firstWhere('is_main', true) ?? $category->images->first();
                $countryImgSrc = $countryImg?->path ?? 'images/i-love-bootstrap2.png';
            @endphp
            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.pais', $category->slug) }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset($countryImgSrc) }}" alt="Imagen de {{$category->name}}" title="{{$category->name}}" class="card-img" loading="lazy" decoding="async">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title" title="{{$category->name}}"><i class="bi bi-geo-alt-fill"></i>{{$category->name}}</h5>
                            @if (isset($prices[$category->id]))
                                <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde {{ $prices[$category->id] }}&euro;">{{ $prices[$category->id] }}&euro;</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            @empty
                <x-other-destinations>
                    Actualmente no hay paises disponibles en estos momentos.
                    ¡Vuelve pronto o consulta otras provincias!
                </x-other-destinations>
            @endforelse
            
        </div>

    </section>
    <!-- /container -->
@endsection