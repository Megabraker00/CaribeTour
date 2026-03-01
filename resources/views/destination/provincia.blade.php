@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))


@section('content')
    <!-- container -->
    <section class="container py-4">        

        <div class="row">

            <div class="col-sm-12 col-md-12 col-lg-9 mb-4">
                <h1 class="mb-4"><strong>Punta Cana</strong></h1>
                <hr>
                
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <img width="100%" src="{{ asset('images/i-love-bootstrap2.png') }}"alt="...">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2>Hotel Carolina</h2>

                        <ul class="tour-info">
                            <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                            <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> Punta Cana</li>
                            <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> Sábado 15 de Septiempre 2024</li>
                            <li title="Precio por persona"><span class="fs-4"><i class="bi bi-tag-fill"></i><strong>Desde:</strong> 507.65€</span></li>
                        </ul>

                        <div>
                            <a href="{{ route('destinos.tour', ['country' => 'republica-dominicana', 'province' => 'punta-cana', 'tour' => 'hotel-carolina']) }}" class="btn btn-primary me-2">Ver Fechas</a>
                            <button class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</button>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <img width="100%" src="{{ asset('images/i-love-bootstrap3.png') }}" alt="...">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2>Hotel Carolina</h2>

                        <ul class="tour-info">
                            <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                            <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> Punta Cana</li>
                            <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> Sábado 15 de Septiempre 2024</li>
                            <li title="Precio por persona"><span class="fs-4"><i class="bi bi-tag-fill flip-horizontal"></i><strong>Desde:</strong> 507.65€</span></li>
                        </ul>

                        <div>
                            <button class="btn btn-primary me-2">Ver Fechas</button>
                            <button class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</button>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <img width="100%" src="{{ asset('images/i-love-bootstrap1.png') }}" alt="...">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2>Hotel Carolina</h2>

                        <ul class="tour-info">
                            <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                            <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> Punta Cana</li>
                            <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> Sábado 15 de Septiempre 2024</li>
                            <li title="Precio por persona"><strong>Desde: </strong><span class="fs-4">507.65€</span><i class="bi bi-tag-fill"></i></li>
                        </ul>

                        <div>
                            <button class="btn btn-primary me-2">Ver Fechas</button>
                            <button class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</button>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <img width="100%" src="{{ asset('images/i-love-bootstrap2.png') }}" alt="...">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2>Hotel Carolina</h2>

                        <ul class="tour-info">
                            <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                            <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> Punta Cana</li>
                            <li title="Precio por persona"><span class="fs-4"><i class="bi bi-tag-fill"></i><strong>Desde:</strong> 507.65€</span></li>
                        </ul>

                        <div>
                            <button class="btn btn-primary me-2">Ver Fechas</button>
                            <button class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</button>
                        </div>
                    </div>
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