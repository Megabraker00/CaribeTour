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

            <!-- TABS -->
            <div class="col-md-4 col-lg-3">
                <ul class="nav nav-tabs flex-column nav-fill" id="serviceTabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vuelos" type="button">
                            <i class="bi bi-airplane-fill"></i> Vuelos
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hoteles" type="button">
                            <i class="bi bi-building-fill-check"></i> Hoteles
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cruceros" type="button">
                            <i class="bi bi-train-freight-front-fill"></i> Cruceros
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#trenes" type="button">
                            <i class="bi bi-train-front-fill"></i> Trenes
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#excursiones" type="button">
                            <i class="bi bi-map-fill"></i> Excursiones
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#seguros" type="button">
                            <i class="bi bi-shield-lock-fill"></i> Seguros
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#souvenirs" type="button">
                            <i class="bi bi-gift-fill"></i> Souvenirs
                        </button>
                    </li>

                </ul>
            </div>

            <!-- CONTENIDO -->
            <div class="col-md-8 col-lg-9">
                <div class="tab-content p-4 h-100">

                    <div class="tab-pane fade show active" id="vuelos">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <h3>Vuelos</h3>
                                <p>Reserva vuelos nacionales e internacionales al mejor precio.
                                    Ofrecemos una amplia selección de aerolíneas y destinos para que encuentres el vuelo perfecto para tu viaje al Caribe.</p>
                                <p>Encuentra ofertas exclusivas, vuelos directos y opciones flexibles para que tu experiencia de viaje sea inolvidable. ¡Reserva ahora y vuela al paraíso del Caribe con CaribeTour!</p>
                                </p>
                                
                            </div>
                            <div class="col-md-5 col-lg-5">
                                <img src="{{ asset('images/ticket_aereo.jpg') }}" class="img-fluid" alt="Vuelos al Caribe">
                            </div>
                        </div>                        
                    </div>

                    <div class="tab-pane fade" id="trenes">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <h3>Trenes</h3>
                                <p>Billetes de alta velocidad y trenes regionales.</p>
                                <p>Explora el Caribe a bordo de nuestros trenes de alta velocidad y regionales. Ofrecemos billetes para rutas panorámicas que te permitirán disfrutar de paisajes impresionantes mientras viajas cómodamente. Reserva ahora y descubre el Caribe de una manera única con CaribeTour.</p>
                            </div>
                            <div class="col-md-5 col-lg-5">
                                <img src="{{ asset('images/trenes.jpg') }}" class="img-fluid" alt="Trenes en el Caribe">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="hoteles">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <h3>Hoteles</h3>
                                <p>Hoteles, apartamentos y resorts con ofertas exclusivas.</p>
                                <p>Encuentra el alojamiento perfecto para tu viaje al Caribe con CaribeTour. Ofrecemos una amplia selección de hoteles, apartamentos y resorts en los destinos más populares del Caribe, desde playas paradisíacas hasta ciudades vibrantes. Reserva ahora y disfruta de ofertas exclusivas, atención personalizada y una experiencia inolvidable en el paraíso del Caribe.</p>
                            </div>
                            <div class="col-md-5 col-lg-5">
                                <img src="{{ asset('images/hoteles.jpg') }}" class="img-fluid" alt="Hoteles en el Caribe">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="excursiones">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <h3>Excursiones</h3>
                                <p>Tours guiados, entradas y experiencias.</p>
                                <p>Descubre los mejores lugares del Caribe con nuestras excursiones personalizadas. Desde tours históricos hasta aventuras naturales, ofrecemos una amplia variedad de experiencias para que disfrutes de todo lo que el Caribe tiene que ofrecer.</p>
                            </div>
                            <div class="col-md-5 col-lg-5">
                                <img src="{{ asset('images/excursiones.jpg') }}" class="img-fluid " alt="Hoteles en el Caribe">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="seguros">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <h3>Seguros</h3>
                                <p>Seguro de viaje, cancelaciones y asistencia médica.</p>
                                <p>Viaja con tranquilidad al Caribe con nuestros seguros de viaje. Ofrecemos cobertura para cancelaciones, asistencia médica y protección contra imprevistos para que disfrutes de tu viaje sin preocupaciones. ¡Reserva ahora y viaja seguro con CaribeTour!</p>
                            </div>
                            <div class="col-md-5 col-lg-5">
                                <img src="{{ asset('images/seguros.jpg') }}" class="img-fluid" alt="Seguros de viaje al Caribe">
                            </div>                            
                        </div>
                    </div>

                    <div class="tab-pane fade" id="souvenirs">
                        <h3>Souvenirs</h3>
                        <p>Regalos y recuerdos de tus viajes.</p>
                    </div>

                    <div class="tab-pane fade" id="cruceros">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <h3>Cruceros</h3>
                                <p>Cruceros de lujo y experiencias marinas.</p>
                                <p>Explora el Caribe a bordo de un crucero de lujo con CaribeTour. Ofrecemos una amplia selección de cruceros que te llevarán a los destinos más impresionantes del Caribe, desde playas paradisíacas hasta ciudades vibrantes. Disfruta de experiencias marinas inolvidables, servicios exclusivos y atención personalizada mientras navegas por las aguas cristalinas del Caribe. Reserva ahora y vive la aventura de tu vida con CaribeTour.</p>
                            </div>
                            <div class="col-md-5 col-lg-5">
                                <img src="{{ asset('images/cruceros.jpg') }}" class="img-fluid" alt="Cruceros en el Caribe">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </section>
    <!-- /container -->
@endsection