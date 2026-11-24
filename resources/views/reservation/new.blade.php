@extends('front_template')
@section('title', 'Reservar')
@section('description', 'Completa los datos del titular y de los pasajeros para reservar esta salida.')
@section('robots', 'noindex, nofollow')

@section('content')
    <!-- container -->
    <section class="container my-4">

        <h1 class="h3 mb-4">Reservar</h1>

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
                                    <li title="{{ $tour->starsLabel() }}"><i class="bi bi-trophy-fill" aria-hidden="true"></i><strong>Categoría:</strong>
                                        @if ($tour->stars() > 0)
                                            <span class="star-{{ $tour->stars() }} fs-6" role="img" aria-label="{{ $tour->starsLabel() }}"></span>
                                        @else
                                            <span class="text-muted small">Sin clasificar</span>
                                        @endif
                                    </li>
                                    <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><strong>Destino:</strong> {{$tour->category}} - {{$tour->category?->parentCategory}}</li>
                                    <li><i class="bi bi-arrow-up-right-square-fill" aria-hidden="true"></i><strong>Salida:</strong> {{ ucfirst(\Carbon\Carbon::parse($tourDeparture)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-arrow-down-left-square-fill" aria-hidden="true"></i><strong>Regreso:</strong> {{ ucfirst(\Carbon\Carbon::parse($tourReturn)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}</li>
                                    <li><i class="bi bi-calendar-week-fill" aria-hidden="true"></i><strong>Duración:</strong> {{$days}} Días - {{$nights}} Noches</li>
                                    @if(!empty($tour->meta['includes']))
                                    <li>
                                        <i class="bi bi-ui-checks" aria-hidden="true"></i><strong>Incluye:</strong>
                                        <ul class="mt-2">
                                            @foreach($tour->meta['includes'] as $include)
                                                <li>{{ $include }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif
                                    <li title="Precio por persona"><i class="bi bi-tag-fill" aria-hidden="true"></i><strong class="fs-5">Precio por Persona: </strong><span class="fs-4 fw-bold">{{$price}}&euro;</span></li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="card-footer fs-3">
                             <strong>
                                <i class="bi bi-cash-stack" aria-hidden="true"></i> TOTAL:
                                @if ($soldOut)
                                    <span>Sin plazas</span>
                                @else
                                    <span id="reservation-total" title="Total a pagar" aria-live="polite" aria-atomic="true">{{ number_format($price * $quantity, 2, ',', '.') }}&euro;</span>
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 col-sm-12 col-lg-8">
                @if ($soldOut)
                    <div class="alert alert-warning">
                        <p class="mb-3">Esta salida no tiene plazas disponibles.</p>
                        @if ($hasOtherDepartures)
                            <a href="{{ $tourUrl ?? route('destinos') }}" class="btn btn-primary">Ver otras fechas</a>
                        @endif
                    </div>
                    @unless ($hasOtherDepartures)
                        <x-other-destinations>
                            Este tour no tiene otras salidas con plazas.
                            ¡Vuelve pronto o consulta otros destinos!
                        </x-other-destinations>
                    @endunless
                @else
                @if (session('error'))
                    <div class="alert alert-warning">{{ session('error') }}</div>
                @endif
                @php
                    $looseErrors = collect($errors->getMessages())->reject(function ($messages, $key) {
                        return str_starts_with($key, 'customer_') || str_starts_with($key, 'passengers.');
                    })->flatten();
                @endphp
                @if ($looseErrors->isNotEmpty())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($looseErrors as $error)
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
                            <div class="col-12">
                                <p class="text-muted">Todos los campos son obligatorios, excepto las observaciones.</p>
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" required autocomplete="section-titular given-name" value="{{ old('customer_name') }}" @error('customer_name') aria-invalid="true" aria-describedby="customer_name-error" @enderror>
                                @error('customer_name')
                                    <div id="customer_name-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_last_name" class="form-label">Apellidos</label>
                                <input type="text" class="form-control @error('customer_last_name') is-invalid @enderror" id="customer_last_name" name="customer_last_name" required autocomplete="section-titular family-name" value="{{ old('customer_last_name') }}" @error('customer_last_name') aria-invalid="true" aria-describedby="customer_last_name-error" @enderror>
                                @error('customer_last_name')
                                    <div id="customer_last_name-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_nationality" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control @error('customer_nationality') is-invalid @enderror" id="customer_nationality" name="customer_nationality" required value="{{ old('customer_nationality') }}" @error('customer_nationality') aria-invalid="true" aria-describedby="customer_nationality-error" @enderror>
                                @error('customer_nationality')
                                    <div id="customer_nationality-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_document" class="form-label">Pasaporte / DNI</label>
                                <input type="text" class="form-control @error('customer_document') is-invalid @enderror" id="customer_document" name="customer_document" required value="{{ old('customer_document') }}" @error('customer_document') aria-invalid="true" aria-describedby="customer_document-error" @enderror>
                                @error('customer_document')
                                    <div id="customer_document-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" required autocomplete="section-titular email" value="{{ old('customer_email') }}" @error('customer_email') aria-invalid="true" aria-describedby="customer_email-error" @enderror>
                                @error('customer_email')
                                    <div id="customer_email-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 col-lg-6 mb-3">
                                <label for="customer_phone" class="form-label">Teléfono de Contacto</label>
                                <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" required autocomplete="section-titular tel" placeholder="+34..." value="{{ old('customer_phone') }}" @error('customer_phone') aria-invalid="true" aria-describedby="customer_phone-error" @enderror>
                                @error('customer_phone')
                                    <div id="customer_phone-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. DATOS DE LOS PASAJEROS --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">Pasajeros</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="copy-holder-to-passenger-1" title="Rellenar el primer pasajero con los datos del titular">
                                    <i class="bi bi-person-down" aria-hidden="true"></i> Copiar datos del titular
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
                                            <input type="text" id="passengers[{{ $i }}][first_name]" name="passengers[{{ $i }}][first_name]" class="form-control @error('passengers.'.$i.'.first_name') is-invalid @enderror" required autocomplete="section-pasajero-{{ $i }} given-name" value="{{ old('passengers.'.$i.'.first_name') }}" @error('passengers.'.$i.'.first_name') aria-invalid="true" aria-describedby="passengers[{{ $i }}][first_name]-error" @enderror>
                                            @error('passengers.'.$i.'.first_name')
                                                <div id="passengers[{{ $i }}][first_name]-error" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][last_name]" class="form-label">Apellidos</label>
                                            <input type="text" id="passengers[{{ $i }}][last_name]" name="passengers[{{ $i }}][last_name]" class="form-control @error('passengers.'.$i.'.last_name') is-invalid @enderror" required autocomplete="section-pasajero-{{ $i }} family-name" value="{{ old('passengers.'.$i.'.last_name') }}" @error('passengers.'.$i.'.last_name') aria-invalid="true" aria-describedby="passengers[{{ $i }}][last_name]-error" @enderror>
                                            @error('passengers.'.$i.'.last_name')
                                                <div id="passengers[{{ $i }}][last_name]-error" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][nationality]" class="form-label">Nacionalidad</label>
                                            <input type="text" id="passengers[{{ $i }}][nationality]" name="passengers[{{ $i }}][nationality]" class="form-control @error('passengers.'.$i.'.nationality') is-invalid @enderror" required value="{{ old('passengers.'.$i.'.nationality') }}" @error('passengers.'.$i.'.nationality') aria-invalid="true" aria-describedby="passengers[{{ $i }}][nationality]-error" @enderror>
                                            @error('passengers.'.$i.'.nationality')
                                                <div id="passengers[{{ $i }}][nationality]-error" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][document]" class="form-label">Pasaporte / DNI</label>
                                            <input type="text" id="passengers[{{ $i }}][document]" name="passengers[{{ $i }}][document]" class="form-control @error('passengers.'.$i.'.document') is-invalid @enderror" required value="{{ old('passengers.'.$i.'.document') }}" @error('passengers.'.$i.'.document') aria-invalid="true" aria-describedby="passengers[{{ $i }}][document]-error" @enderror>
                                            @error('passengers.'.$i.'.document')
                                                <div id="passengers[{{ $i }}][document]-error" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][gender]" class="form-label">Genero</label>
                                            <select id="passengers[{{ $i }}][gender]" name="passengers[{{ $i }}][gender]" class="form-control @error('passengers.'.$i.'.gender') is-invalid @enderror" required @error('passengers.'.$i.'.gender') aria-invalid="true" aria-describedby="passengers[{{ $i }}][gender]-error" @enderror>
                                                <option value="" @selected(old('passengers.'.$i.'.gender') === null || old('passengers.'.$i.'.gender') === '')>Selecciona</option>
                                                <option value="male" @selected(old('passengers.'.$i.'.gender') === 'male')>Masculino</option>
                                                <option value="female" @selected(old('passengers.'.$i.'.gender') === 'female')>Femenino</option>
                                            </select>
                                            @error('passengers.'.$i.'.gender')
                                                <div id="passengers[{{ $i }}][gender]-error" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-lg-6 mb-3">
                                            <label for="passengers[{{ $i }}][birth_date]" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" id="passengers[{{ $i }}][birth_date]" name="passengers[{{ $i }}][birth_date]" class="form-control @error('passengers.'.$i.'.birth_date') is-invalid @enderror" required value="{{ old('passengers.'.$i.'.birth_date') }}" @error('passengers.'.$i.'.birth_date') aria-invalid="true" aria-describedby="passengers[{{ $i }}][birth_date]-error" @enderror>
                                            @error('passengers.'.$i.'.birth_date')
                                                <div id="passengers[{{ $i }}][birth_date]-error" class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-passenger" @disabled($quantity >= $maxPassengers)>
                                <i class="bi bi-person-plus" aria-hidden="true"></i> Añadir otro pasajero
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
                @endif
            </div>

            
        </div>

    </section>
    <!-- /container -->

    @unless ($soldOut)
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
                field.classList.remove('is-invalid');
                field.removeAttribute('aria-invalid');
                field.removeAttribute('aria-describedby');
            });
            copy.querySelectorAll('.invalid-feedback').forEach(function (error) {
                error.remove();
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
                block.querySelectorAll('input, select, label, .invalid-feedback').forEach(function (field) {
                    if (field.name) {
                        field.name = field.name.replace(/passengers\[\d+\]/, 'passengers[' + number + ']');
                    }
                    if (field.id) {
                        field.id = field.id.replace(/passengers\[\d+\]/, 'passengers[' + number + ']');
                    }
                    if (field.htmlFor) {
                        field.htmlFor = field.htmlFor.replace(/passengers\[\d+\]/, 'passengers[' + number + ']');
                    }
                    const describedBy = field.getAttribute('aria-describedby');
                    if (describedBy) {
                        field.setAttribute('aria-describedby', describedBy.replace(/passengers\[\d+\]/, 'passengers[' + number + ']'));
                    }
                    const autocomplete = field.getAttribute('autocomplete');
                    if (autocomplete && autocomplete.indexOf('section-pasajero-') !== -1) {
                        field.setAttribute('autocomplete', autocomplete.replace(/section-pasajero-\d+/, 'section-pasajero-' + number));
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
    @endunless
@endsection