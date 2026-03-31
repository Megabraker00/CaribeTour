@extends('front_template')
@section('title', 'Reserva confirmada ' . $booking->external_ref . ' - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
<!-- container -->
    <section class="container my-4">

        <div class="row">

            <div class="col-md-6 col-sm-12 col-lg-4 order-last order-lg-first">
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

            <div class="col-md-6 col-sm-12 col-lg-8 order-first order-lg-last" id="printable-area">

                <div class=" justify-content-center">

                    {{-- 1. MENSAJE DE ÉXITO --}}
                    <div class="card shadow-sm text-center">

                        <div class="card-body">
                            <div class="mb-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h1 class="display-5 fw-bold">¡Pago Confirmado!</h1>

                            <p class="lead text-muted">Gracias por confiar en nosotros. Tu reserva para <strong>{{ $product->name }}</strong> ha sido procesada con éxito.</p>

                            <div class="badge bg-light fs-5 text-dark border p-2 mt-2">
                                Número de reserva: <span class="fw-bold">{{ $booking->external_ref }}</span>
                            </div>
                            
                        </div>

                    </div>

                    {{-- 2. DETALLES DEL TITULAR --}}

                    <div class="row">

                        <div class="col-sm-12 col-md-12 col-lg-8">
                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-white py-4">
                                    <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detalles</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                                            <span class="text-muted">Titular:</span>
                                            <span class="fw-bold"></span>{{$booking->client}}
                                        </li>
                                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                                            <span class="text-muted">Email de notificaci&oacute;n:</span>
                                            <span class="fw-bold">{{$booking->client->email}}</span>
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

                        {{-- 3. RESUMEN DEL PAGO --}}
                        <div class="col-sm-12 col-md-12 col-lg-4">
                            <div class="card shadow-sm mt-4 bg-light">
                                <div class="card-body">
                                    <h4 class="card-tile">Resumen del Pago</h4>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Precio Base:</span>
                                        <span>{{ number_format($price, 2, ',', '.') }}€</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="h5 mb-0">Total Pagado:</span>
                                        <span class="h4 mb-0 fw-bold text-primary">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button onclick="window.print();" class="btn btn-outline-dark">
                                            <i class="bi bi-printer me-2"></i>Imprimir Recibo
                                        </button>
                                        <a href="{{ url('/') }}" class="btn btn-primary">
                                            Volver al Inicio
                                        </a>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- 4. PRÓXIMOS PASOS --}}
                    <div class="my-4 p-4 bg-white rounded shadow-sm border-start border-success border-4">
                        <h5>¿Qué sigue ahora?</h5>
                        <p class="text-muted mb-0">
                            En breve recibirás un correo electrónico con los bonos de viaje y la factura detallada. 
                            Si tienes alguna duda, contacta con nuestro equipo citando tu referencia <strong>{{ $booking->external_ref }}</strong>.
                        </p>
                    </div>


                </div>               

            </div>

        </div>

    </section>
    <!-- /container -->
@endsection
