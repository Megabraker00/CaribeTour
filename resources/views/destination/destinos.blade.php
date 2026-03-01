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

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.pais', 'republica-dominicana') }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap2.png') }}" alt="Imagen del hotel Grand Palladium Punta Cana Resort & Spa" title="Grand Palladium Punta Cana Resort & Spa" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title" title="Hotel Grand Palladium Punta Cana Resort & Spa"><i class="bi bi-geo-alt-fill"></i> República. Dominicana</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.pais', 'mexico') }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap1.png') }}" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title"><i class="bi bi-geo-alt-fill"></i> Mexico</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.pais', 'cuba') }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap3.png') }}" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title"><i class="bi bi-geo-alt-fill"></i> Cuba</h5>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </section>
    <!-- /container -->
@endsection