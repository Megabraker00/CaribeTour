@extends('front_template')
@section('title', $service->name)
@section('description', $service->meta['description'] ?? 'Servicios de viaje con CaribeTour.')
@section('og_title', $service->name)
@section('og_description', $service->meta['description'] ?? 'Servicios de viaje con CaribeTour.')

@section('content')
    <section class="container my-4">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <img
                    src="{{ asset($service->mainImage?->path ?? 'images/image_12.jpg') }}"
                    alt="Imagen de {{ $service->name }}"
                    class="img-fluid rounded shadow-sm"
                    loading="lazy"
                    decoding="async"
                >
            </div>
            <div class="col-lg-6 mb-4">
                <p class="text-muted mb-1">{{ $service->type?->name }} · {{ $service->category }}</p>
                <h1 class="h2">{{ $service->name }}</h1>
                <hr>
                @if (!empty($service->meta['description']))
                    <p>{{ $service->meta['description'] }}</p>
                @else
                    <p>Consulta este servicio con nuestro equipo para personalizar tu viaje.</p>
                @endif

                @if (!empty($service->meta['includes']) && is_array($service->meta['includes']))
                    <h2 class="h5 mt-4">Incluye</h2>
                    <ul>
                        @foreach ($service->meta['includes'] as $include)
                            <li>{{ $include }}</li>
                        @endforeach
                    </ul>
                @endif

                <a href="{{ route('contacto') }}" class="btn btn-primary mt-3">Solicitar información</a>
                <a href="{{ route('servicios') }}" class="btn btn-outline-secondary mt-3">Volver a servicios</a>
            </div>
        </div>
    </section>
@endsection
