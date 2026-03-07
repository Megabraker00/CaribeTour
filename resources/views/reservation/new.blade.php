@extends('front_template')
@section('title', 'Elige los mejores destinos del caribe - Especialistas en el Caribe')
@section('description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_title', 'CaribeTour.es - Especialistas en el Caribe og_title')
@section('og_description', 'Viaja al Caribe con CaribeTour, especialistas en vuelos y hoteles a los mejores destinos del Caribe. Ofrecemos ofertas exclusivas, atención personalizada y una amplia selección de paquetes vacacionales para que tu experiencia sea inolvidable.')
@section('og_image', asset('images/og-default.jpg'))

@section('content')
    <!-- container -->
    <section class="container my-4">

        @php
            $quantity = 2;
        @endphp

        <div class="row">

            <div class="col-md-6 col-sm-12 col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Resumen de tu reserva</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p>
                            <img loading="lazy" src="{{ asset($tour->mainImage?->path ?? 'images/image_12.jpg') }}" alt="Imagen del {{$tour->name}}" title="{{$tour->name}}" class="img-aspect-4-3 card-img img-fluid">
                            </p>
                            <h5 class="card-title" title="{{$tour->name}}">{{$tour->name}}</h5>
                            <div class="card-subtitle mb-2 text-muted" title="Resumen">

                                <ul class="tour-info">
                                    <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-5 fs-6"></span> </li>
                                    <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> {{$tour->category}} - {{$tour->category?->parentCategory}}</li>
                                    <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> {{ ucfirst(\Carbon\Carbon::parse($tourDeparture)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-arrow-down-left-square-fill"></i><strong>Regreso:</strong> {{ ucfirst(\Carbon\Carbon::parse($tourReturn)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-calendar-week-fill"></i><strong>Duración:</strong> {{$days}} Días - {{$nights}} Noches</li>
                                    @if(!empty($tour->meta['includes']))
                                        <i class="bi bi-ui-checks"></i> Incluye
                                        <ul>
                                            @foreach($tour->meta['includes'] as $include)
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

            <div class="col-md-6 col-sm-12 col-lg-8">
                <form action="{{ route('reservation.store', ['product' => $tour, 'itinerary' => $itinerary]) }}" method="POST">
                    @csrf

                    {{-- 1. DATOS DEL TOUR (CAMPOS OCULTOS) --}}
                    {{-- Estos datos vienen de la selección previa del usuario --}}
                    <input type="hidden" name="itId" value="{{ $itinerary->id }}">
                    <input type="hidden" name="quantity" value="{{-- $quantity --}}"> {{-- Cantidad de plazas bloqueadas --}}

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Titular</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required value="{{-- auth()->user()->name ?? '' --}}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_last_name" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" required value="{{-- auth()->user()->last_name ?? '' --}}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_nationality" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control" id="customer_nationality" name="customer_nationality" required value="{{-- auth()->user()->last_name ?? '' --}}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_document" class="form-label">Pasaporte / DNI</label>
                                <input type="text" class="form-control" id="customer_document" name="customer_document" required value="{{-- auth()->user()->last_name ?? '' --}}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" required value="{{-- auth()->user()->email ?? '' --}}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_phone" class="form-label">Teléfono de Contacto</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" placeholder="+34...">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="holder_is_passenger" id="holder_is_passenger" checked>
                                    <label class="form-check-label" for="holder_is_passenger">
                                        Yo soy uno de los pasajeros
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. DATOS DE LOS PASAJEROS --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Pasajeros</h5>
                        </div>
                        <div class="card-body">
                            {{-- Generamos tantos bloques de pasajeros como 'quantity' se haya elegido --}}
                            @for ($i = 1; $i <= $quantity; $i++)
                                <div class="passenger-block mb-4 pb-3">
                                    <h6>Pasajero #{{ $i }}</h6>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][first_name]" class="form-label">Nombre</label>
                                            <input type="text" id="passengers[{{ $i }}][first_name]" name="passengers[{{ $i }}][first_name]" class="form-control" required>
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][last_name]" class="form-label">Apellidos</label>
                                            <input type="text" id="passengers[{{ $i }}][last_name]" name="passengers[{{ $i }}][last_name]" class="form-control" required>
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][nationality]" class="form-label">Nacionalidad</label>
                                            <input type="text" id="passengers[{{ $i }}][nationality]" name="passengers[{{ $i }}][nationality]" class="form-control" required value="{{-- auth()->user()->last_name ?? '' --}}">
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][document]" class="form-label">Pasaporte / DNI</label>
                                            <input type="text" id="passengers[{{ $i }}][document]" name="passengers[{{ $i }}][document]" class="form-control" required>
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">                                            
                                            <label for="passengers[{{ $i }}][gender]" class="form-label">Genero</label>
                                            <select name="passengers[{{ $i }}][gender]" class="form-control" required>
                                                <option> - </option>
                                                <option value="male">Masculino</option>
                                                <option value="female">Femenino</option>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][birth_date]" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" id="passengers[{{ $i }}][birth_date]" name="passengers[{{ $i }}][birth_date]" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- 3. COMENTARIOS Y ENVÍO --}}
                    <div class="mb-4">
                        <label for="notes" class="form-label">Observaciones adicionales (Alergias, peticiones especiales...)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Confirmar y proceder al pago</button>
                    </div>
                </form>
            </div>

            
        </div>

    </section>
    <!-- /container -->
@endsection