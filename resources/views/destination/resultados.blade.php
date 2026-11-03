@extends('front_template')
@section('title', 'Resultados de búsqueda')
@section('description', 'Tours disponibles según destino, origen y fecha de salida.')
@section('robots', 'noindex, follow')

@section('content')
    <section class="container my-4">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-9 mb-4">
                <h1 class="mb-4"><strong>Resultados</strong></h1>

                @if ($destination || $origin || $departure)
                    <p>
                        @if ($destination)
                            Destino: {{ $destination->name }}.
                        @endif
                        @if ($origin)
                            Origen: {{ $origin->name }}.
                        @endif
                        @if ($departure)
                            Desde el {{ $departure->locale('es')->translatedFormat('j \d\e F \d\e Y') }}.
                        @endif
                    </p>
                @endif

                <hr>

                @forelse ($tours as $tour)
                    @php
                        $itinerary = $tour->itineraries->first();
                        $firstSeg = $itinerary?->segments->first();
                        $province = $tour->category;
                        $countrySlug = $province?->parentCategory?->slug;
                        $tourImage = $tour->images->first(fn ($image) => (bool) $image->is_main) ?? $tour->images->first();
                        $tourUrl = ($countrySlug && $province)
                            ? route('destinos.tour', ['country' => $countrySlug, 'province' => $province->slug, 'tour' => $tour->slug])
                            : route('inicio');
                    @endphp

                    <div class="row mb-4">
                        <div class="col-md-6 mb-4">
                            <a href="{{ $tourUrl }}">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ asset($tourImage?->path ?? 'images/image_12.jpg') }}" alt="Imagen del tour {{ $tour->name }}" loading="lazy" decoding="async" title="{{ $tour->name }}" class="card-img img-aspect-16-9">
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a class="text-decoration-none" href="{{ $tourUrl }}" title="{{ $tour->name }}">
                                <h2>{{ $tour->name }}</h2>
                            </a>

                            <ul class="tour-info">
                                <li title="Categoría: {{ $tour->stars() }} estrellas"><i class="bi bi-trophy-fill"></i><strong>Categoría:</strong> <span class="star-{{ $tour->stars() }} fs-6"></span></li>
                                <li title="{{ $province?->name }}"><i class="bi bi-geo-alt-fill"></i><strong>Destino:</strong> {{ $province?->name }} - {{ $province?->parentCategory }}</li>
                                <li>
                                    <i class="bi bi-arrow-up-right-square-fill"></i><strong>Salida:</strong>
                                    @if ($firstSeg?->departure_date)
                                        {{ ucfirst(\Carbon\Carbon::parse($firstSeg->departure_date)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}
                                    @else
                                        <span class="text-muted">Ver fechas</span>
                                    @endif
                                </li>
                                <li title="Precio por persona">
                                    <span class="fs-4">
                                        <i class="bi bi-tag-fill"></i><strong>Desde:</strong>
                                        @if ($itinerary)
                                            {{ number_format($itinerary->fullPrice(), 2, ',', '.') }}€
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>

                            <div class="mt-4">
                                <a title="Ver fechas disponibles para {{ $tour->name }}" href="{{ $tourUrl }}" class="btn btn-primary me-2">Ver Fechas</a>
                                <a href="{{ route('contacto', ['tour' => $tour->slug]) }}" class="btn btn-secondary" title="Realizar una consulta sobre este tour">Consultar</a>
                            </div>
                        </div>
                    </div>

                    @if (!$loop->last)
                        <hr>
                    @endif
                @empty
                    <p>No hay salidas con esos filtros. Prueba con otro destino, otro origen o una fecha posterior.</p>
                @endforelse

                <div class="mt-4 d-flex justify-content-center">
                    {!! $tours->links() !!}
                </div>
            </div>

            <div class="col-md-12 col-lg-3 mb-4">
                @include('components.buscador')
                @include('components.promo')
            </div>
        </div>
    </section>
@endsection
