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

            <div class="col-md-6 col-sm-12 col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Resumen de tu reserva</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p>
                            <img loading="lazy" src="{{ asset($product->mainImage?->path ?? 'images/image_12.jpg') }}" alt="Imagen del {{$product->name}}" title="{{$product->name}}" class="img-aspect-4-3 card-img img-fluid">
                            </p>
                            <h5 class="card-title" title="{{$product->name}}">{{$product->name}}</h5>
                            <div class="card-subtitle mb-2 text-muted" title="Resumen">
                                <ul class="tour-info">
                                    <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                                    <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> {{$product->category}} - {{$product->category?->parentCategory}}</li>
                                    <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> {{ ucfirst(\Carbon\Carbon::parse($productDeparture)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-arrow-down-left-square-fill"></i><strong>Regreso:</strong> {{ ucfirst(\Carbon\Carbon::parse($productReturn)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-calendar-week-fill"></i><strong>Duración:</strong> {{$days}} Días - {{$nights}} Noches</li>
                                    @if(!empty($product->meta['includes']))
                                    <i class="bi bi-ui-checks"></i><strong>Incluye: </strong>
                                        <ul>
                                            @foreach($product->meta['includes'] as $include)
                                                <li>{{ $include }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <li title="Precio por persona"><i class="bi bi-tag-fill"></i><strong class="fs-5">Precio por Persona: </strong><span class="fs-4 fw-bold">{{$price}}&euro;</span></li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="card-footer fs-3">                            
                             <strong>
                                <i class="bi bi-cash-stack"></i> TOTAL:   <span title="Total a pagar">{{ number_format($price * 2, 2, ',', '.') }}&euro;</span>
                            </strong>                            
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 col-sm-12 col-lg-8 order-first order-lg-last">

                <div class="justify-content-center">

                    {{-- 1. MENSAJE DE ERROR --}}
                    <div class="card shadow-sm text-center border-danger">
                        <div class="card-body">
                            <div class="mb-4">
                                {{-- Cambiamos el check por una X de error --}}
                                <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h1 class="display-5 fw-bold">Ups... El pago ha fallado</h1>

                            <p class="lead text-muted">
                                No hemos podido procesar el pago para tu reserva en <strong>{{ $product->name }}</strong>. 
                                Esto puede deberse a fondos insuficientes, tarjeta caducada o un bloqueo de seguridad de tu banco.
                            </p>

                            <div class="badge bg-light fs-5 text-dark border p-2 mt-2">
                                Número de reserva: <span class="fw-bold">{{ $booking->external_ref }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. DETALLES Y RESUMEN --}}
                    <div class="row">
                        {{-- DETALLES --}}
                        <div class="col-sm-12 col-md-12 col-lg-8">
                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-white py-4">
                                    <h4 class="mb-0 text-muted"><i class="bi bi-info-circle me-2"></i>Tu Reserva Sigue Pendiente</h4>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted">Aún tenemos guardados tus datos, pero la plaza no estará confirmada hasta que se complete el pago.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                                            <span class="text-muted">Titular:</span>
                                            <span class="fw-bold">{{ $booking->client }}</span>
                                        </li>
                                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                                            <span class="text-muted">Email de notificaci&oacute;n:</span>
                                            <span class="fw-bold">{{ $booking->client->email }}</span>
                                        </li>
                                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                                            <span class="text-muted">Pasajeros:</span>
                                            <ol>
                                            @foreach ($booking->passengers as $passenger)
                                                <li>{{$passenger}} | {{$passenger->dni_passport}}</li>
                                            @endforeach
                                            </ol>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- RESUMEN DEL PAGO Y BOTONES ACCIÓN --}}
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <div class="card shadow-sm mt-4 bg-white border-top border-danger border-4">
                                <div class="card-body">
                                    <h4 class="card-title">Importe a Pagar</h4>
                                    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                                        <span class="h4 mb-0 fw-bold text-dark">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        {{-- BOTÓN REINTENTAR: Apunta de vuelta a la página de pago con el formulario de Stripe --}}
                                        <a href="{{ route('reservation.payment', [$product->slug, $itinerary->id]) }}" class="btn btn-danger btn-lg shadow-sm">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Volver a intentarlo
                                        </a>
                                        
                                        <a href="{{ url('/') }}" class="btn btn-link text-muted">
                                            Cancelar y volver al inicio
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. AYUDA --}}
                    <div class="my-4 p-4 bg-light rounded shadow-sm border-start border-warning border-4">
                        <h5>¿Necesitas ayuda?</h5>
                        <p class="text-muted mb-0 small">
                            Si el problema persiste, te recomendamos probar con otra tarjeta o contactar directamente con tu entidad bancaria. 
                            También puedes llamarnos al <span class="fw-bold">+34 000 000 000</span> indicando tu referencia <strong>{{ $booking->external_ref }}</strong>.
                        </p>
                    </div>

                </div>               

            </div>

        </div>

    </section>
    <!-- /container -->
@endsection