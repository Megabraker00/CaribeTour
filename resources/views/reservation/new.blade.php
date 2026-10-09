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
            $maxPassengers = min(20, max(0, (int) $itinerary->available_stock));
            $minPassengers = min(2, $maxPassengers);
            $oldPassengerCount = is_array(old('passengers')) ? count(old('passengers')) : 0;
            $quantity = $oldPassengerCount > 0 ? min($oldPassengerCount, $maxPassengers) : $minPassengers;
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
                                    <li title="Categoría: 5 estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-{{$tour->stars()}} fs-6"></span> </li>
                                    <li><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> {{$tour->category}} - {{$tour->category?->parentCategory}}</li>
                                    <li><i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong> {{ ucfirst(\Carbon\Carbon::parse($tourDeparture)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-arrow-down-left-square-fill"></i><strong>Regreso:</strong> {{ ucfirst(\Carbon\Carbon::parse($tourReturn)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-calendar-week-fill"></i><strong>Duración:</strong> {{$days}} Días - {{$nights}} Noches</li>
                                    @if(!empty($tour->meta['includes']))
                                    <li>
                                        <i class="bi bi-ui-checks"></i><strong>Incluye:</strong>
                                        <ul class="mt-2">
                                            @foreach($tour->meta['includes'] as $include)
                                                <li>{{ $include }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif
                                    <li title="Precio por persona"><i class="bi bi-tag-fill"></i><strong class="fs-5">Precio por Persona: </strong><span class="fs-4 fw-bold">{{$price}}&euro;</span></li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="card-footer fs-3">                            
                             <strong>
                                <i class="bi bi-cash-stack"></i> TOTAL:   <span id="reservation-total" title="Total a pagar">{{ number_format($price * $quantity, 2, ',', '.') }}&euro;</span>
                            </strong>                            
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 col-sm-12 col-lg-8">
                @if (session('error'))
                    <div class="alert alert-warning">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($itinerary->available_stock < $quantity)
                    <div class="alert alert-warning">
                        No hay plazas suficientes para esta salida. Quedan {{ $itinerary->available_stock }}.
                    </div>
                @endif
                <form action="{{ route('reservation.store', ['product' => $tour, 'itinerary' => $itinerary]) }}" method="POST" data-disable-on-submit>
                    @csrf

                    {{-- 1. DATOS DEL TOUR (CAMPOS OCULTOS) --}}
                    {{-- Estos datos vienen de la selección previa del usuario --}}
                    <input type="hidden" name="itId" value="{{ $itinerary->id }}">
                    <input type="hidden" name="quantity" id="quantity" value="{{ $quantity }}">

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Titular</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_last_name" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" required value="{{ old('customer_last_name') }}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_nationality" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control" id="customer_nationality" name="customer_nationality" required value="{{ old('customer_nationality') }}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_document" class="form-label">Pasaporte / DNI</label>
                                <input type="text" class="form-control" id="customer_document" name="customer_document" required value="{{ old('customer_document') }}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" required value="{{ old('customer_email') }}">
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_phone" class="form-label">Teléfono de Contacto</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required placeholder="+34..." value="{{ old('customer_phone') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 2. DATOS DE LOS PASAJEROS --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">Pasajeros</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="copy-holder-to-passenger-1" title="Rellenar el primer pasajero con los datos del titular">
                                    <i class="bi bi-person-down"></i> Copiar datos del titular
                                </button>
                            </div>
                        </div>
                        <div class="card-body" id="passenger-list" data-unit-price="{{ $price }}" data-max="{{ $maxPassengers }}" data-min="{{ $minPassengers }}">
                            @for ($i = 1; $i <= $quantity; $i++)
                                <div class="passenger-block mb-4 pb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 passenger-title">Pasajero #{{ $i }}</h6>
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-passenger" @if($quantity <= $minPassengers) hidden @endif>Quitar</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][first_name]" class="form-label">Nombre</label>
                                            <input type="text" id="passengers[{{ $i }}][first_name]" name="passengers[{{ $i }}][first_name]" class="form-control" required value="{{ old('passengers.'.$i.'.first_name') }}">
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][last_name]" class="form-label">Apellidos</label>
                                            <input type="text" id="passengers[{{ $i }}][last_name]" name="passengers[{{ $i }}][last_name]" class="form-control" required value="{{ old('passengers.'.$i.'.last_name') }}">
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][nationality]" class="form-label">Nacionalidad</label>
                                            <input type="text" id="passengers[{{ $i }}][nationality]" name="passengers[{{ $i }}][nationality]" class="form-control" required value="{{ old('passengers.'.$i.'.nationality') }}">
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][document]" class="form-label">Pasaporte / DNI</label>
                                            <input type="text" id="passengers[{{ $i }}][document]" name="passengers[{{ $i }}][document]" class="form-control" required value="{{ old('passengers.'.$i.'.document') }}">
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">                                            
                                            <label for="passengers[{{ $i }}][gender]" class="form-label">Genero</label>
                                            <select id="passengers[{{ $i }}][gender]" name="passengers[{{ $i }}][gender]" class="form-control" required>
                                                <option value="" @selected(old('passengers.'.$i.'.gender') === null || old('passengers.'.$i.'.gender') === '')>Selecciona</option>
                                                <option value="male" @selected(old('passengers.'.$i.'.gender') === 'male')>Masculino</option>
                                                <option value="female" @selected(old('passengers.'.$i.'.gender') === 'female')>Femenino</option>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][birth_date]" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" id="passengers[{{ $i }}][birth_date]" name="passengers[{{ $i }}][birth_date]" class="form-control" required value="{{ old('passengers.'.$i.'.birth_date') }}">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-passenger" @disabled($quantity >= $maxPassengers)>
                                <i class="bi bi-person-plus"></i> Añadir pasajero
                            </button>
                        </div>
                    </div>

                    {{-- 3. COMENTARIOS Y ENVÍO --}}
                    <div class="mb-4">
                        <label for="notes" class="form-label">Observaciones adicionales (Alergias, peticiones especiales...)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="confirm-reservation" @disabled($quantity < 1)>Confirmar y proceder al pago</button>
                    </div>
                </form>
            </div>

            
        </div>

    </section>
    <!-- /container -->

    @push('scripts')
    <script>
        const passengerList = document.getElementById('passenger-list');
        const quantityInput = document.getElementById('quantity');
        const totalLabel = document.getElementById('reservation-total');
        const addPassengerButton = document.getElementById('add-passenger');
        const unitPrice = Number(passengerList.dataset.unitPrice);
        const maxPassengers = Number(passengerList.dataset.max);
        const minPassengers = Number(passengerList.dataset.min);

        document.getElementById('copy-holder-to-passenger-1').addEventListener('click', function () {
            let name = document.getElementById('customer_name').value.trim();
            let lastName = document.getElementById('customer_last_name').value.trim();
            let nationality = document.getElementById('customer_nationality').value.trim();
            let documentId = document.getElementById('customer_document').value.trim();

            document.querySelector('input[name="passengers[1][first_name]"]').value = name;
            document.querySelector('input[name="passengers[1][last_name]"]').value = lastName;
            document.querySelector('input[name="passengers[1][nationality]"]').value = nationality;
            document.querySelector('input[name="passengers[1][document]"]').value = documentId;
        });

        addPassengerButton.addEventListener('click', function () {
            const blocks = passengerList.querySelectorAll('.passenger-block');
            if (blocks.length >= maxPassengers) {
                return;
            }

            const copy = blocks[blocks.length - 1].cloneNode(true);
            copy.querySelectorAll('input, select').forEach(function (field) {
                field.value = '';
            });
            passengerList.appendChild(copy);
            refreshPassengers();
        });

        passengerList.addEventListener('click', function (event) {
            const button = event.target.closest('.remove-passenger');
            if (!button) {
                return;
            }

            const blocks = passengerList.querySelectorAll('.passenger-block');
            if (blocks.length <= minPassengers) {
                return;
            }

            button.closest('.passenger-block').remove();
            refreshPassengers();
        });

        function formatEuro(amount) {
            const [whole, decimals] = amount.toFixed(2).split('.');
            const grouped = whole.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            return grouped + ',' + decimals + '€';
        }

        function refreshPassengers() {
            const blocks = passengerList.querySelectorAll('.passenger-block');
            blocks.forEach(function (block, index) {
                const number = index + 1;
                block.querySelector('.passenger-title').textContent = 'Pasajero #' + number;
                block.querySelectorAll('input, select, label').forEach(function (field) {
                    if (field.name) {
                        field.name = field.name.replace(/passengers\[\d+\]/, 'passengers[' + number + ']');
                    }
                    if (field.id) {
                        field.id = field.id.replace(/passengers\[\d+\]/, 'passengers[' + number + ']');
                    }
                    if (field.htmlFor) {
                        field.htmlFor = field.htmlFor.replace(/passengers\[\d+\]/, 'passengers[' + number + ']');
                    }
                });
                block.querySelector('.remove-passenger').hidden = blocks.length <= minPassengers;
            });

            quantityInput.value = blocks.length;
            totalLabel.textContent = formatEuro(unitPrice * blocks.length);
            addPassengerButton.disabled = blocks.length >= maxPassengers;
        }
    </script>
    @endpush
@endsection