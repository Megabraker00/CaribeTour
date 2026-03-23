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

            <div class="col-sm-12 col-md-12 col-lg-9 mb-4">
                <h1 class="mb-4"><strong>{{$province->name}}</strong></h1>
                @if (!empty($province->meta['description']))
                    <div class="mb-4 tour-description">{!! $province->meta['description'] !!}</div>
                @endif
                <hr>
                
                @forelse ($tours as $tour)

                    <div class="row mb-4">
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('destinos.tour', ['country' => $countrySlug, 'province' => $province->slug, 'tour' => $tour->slug]) }}">
                            
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ asset($tour?->mainImage?->path ?? 'images/image_12.jpg') }}" alt="Imagen del tour {{$tour->name}}" loading="lazy" decoding="async" title="{{$tour->name}}" class="card-img img-aspect-16-9">
                                    </div>
                                </div>

                            </a>
                        </div>

                        <div class="col-md-6">
                            <a class="text-decoration-none" href="{{ route('destinos.tour', ['country' => $countrySlug, 'province' => $province->slug, 'tour' => $tour->slug]) }}" title="{{$tour->name}}">
                                <h2>{{$tour->name}}</h2>
                            </a>

                            <ul class="tour-info">
                                <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                                <li title="{{$province->name}}"><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> {{$province->name}} - {{$province->parentCategory}}</li>
                                <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> Sábado 15 de Septiempre 2024</li>
                                <li title="Precio por persona"><span class="fs-4"><i class="bi bi-tag-fill"></i><strong>Desde:</strong> 507.65€</span></li>
                            </ul>

                            <div>
                                <a title="Ver fechas disponibles para {{$tour->name}}" href="{{ route('destinos.tour', ['country' => $countrySlug, 'province' => $province->slug, 'tour' => $tour->slug]) }}" class="btn btn-primary me-2">Ver Fechas</a>
                                <button class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</button>
                            </div>
                        </div>
                    </div>

                    @if (!$loop->last)
                        <hr>
                    @endif

                @empty
                    {{-- Esto se mostrará si NO hay tours --}}
                    <div class="alert alert-info text-center shadow-sm">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Actualmente no hay tours disponibles en <strong>{{ $province->name }}</strong>. 
                        ¡Vuelve pronto o consulta otras provincias!
                    </div>
                    
                    {{-- Opcional: Mostrar tours recomendados o un botón de volver --}}
                    <div class="text-center">
                        <a href="{{ url('/') }}" class="btn btn-primary">Ver otros destinos</a>
                    </div>
                @endforelse

                <div class="mt-4 d-flex justify-content-center">
                    {!! $tours->links() !!}
                </div>
                

            </div>

            <!-- buscador y promo -->
            <div class="col-md-12 col-lg-3 mb-4">

                @include('components.buscador')

                @include('components.promo')

            </div>
            <!-- /buscador y promo -->

        </div> 


    </section>
    <!-- /container -->
@endsection