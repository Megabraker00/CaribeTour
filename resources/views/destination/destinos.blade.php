@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
    <!-- container -->
    <section class="container my-4">

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