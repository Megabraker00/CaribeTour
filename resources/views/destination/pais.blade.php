@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
    <!-- container -->
    <section class="container py-4">

        <!-- Destination -->
        <div class="row">

            <div class="col">
                <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-6">
                    <img src="{{ asset('images/i-love-bootstrap2.png') }}" class="img-fluid rounded-start" alt="...">
                  </div>
                  <div class="col-md-6">
                    <div class="card-body">
                      <h3 class="card-title">República Dominicana</h3>
                      <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla earum dolorum consectetur eveniet, illo unde obcaecati at inventore saepe accusamus praesentium voluptates quas officiis consequatur ipsam aperiam! Sit, laboriosam sapiente!</p>
                      <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            

        </div>
        <!-- /Destination -->

        <div class="row pt-4">

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.provincia', ['country' => 'republica-dominicana', 'province' => 'punta-cana']) }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap4.png') }}" alt="Imagen del hotel Grand Palladium Punta Cana Resort & Spa" title="Grand Palladium Punta Cana Resort & Spa" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title" title="Hotel Grand Palladium Punta Cana Resort & Spa">Punta Cana</h5>
                            <div class="card-subtitle mb-2 text-muted" title="Ubicado en Punta Cana - República Dominicana"><i class="bi bi-geo-alt-fill"></i> <small> Punta Cana - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.provincia', ['country' => 'republica-dominicana', 'province' => 'samana']) }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap1.png') }}" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title">Samaná</h5>
                            <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Sanamá - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                <a href="{{ route('destinos.provincia', ['country' => 'republica-dominicana', 'province' => 'santo-domingo']) }}" class="text-decoration-none">
                    <div class="card shadow zoom">
                        <div class="card-body">
                            <img src="{{ asset('images/i-love-bootstrap3.png') }}" class="card-img">
                        </div>
                        <div class="card-footer">
                            <h5 class="card-title">Santo Domingo</h5>
                            <div class="card-subtitle mb-2 text-muted"><i class="bi bi-geo-alt-fill"></i> <small> Santo Domingo - Rep. Dominicana</small></div>
                            <i class="bi bi-tag-fill"></i> Desde <span class="price" title="Precio desde 1126,40&euro;">1126,40&euro;</span>
                        </div>
                    </div>
                </a>
            </div>

            

        </div>

    </section>
    <!-- /container -->
@endsection