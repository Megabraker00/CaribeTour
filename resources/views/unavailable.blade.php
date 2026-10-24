@extends('front_template')
@section('title', 'Destino no disponible')
@section('description', 'Este destino no está disponible en este momento. Consulta otros destinos del Caribe con CaribeTour.')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="container my-4">
        <h1 class="mb-4">Destino no disponible</h1>
        <x-other-destinations>
            Actualmente este destino no está disponible.
            ¡Vuelve pronto o consulta otros destinos!
        </x-other-destinations>
    </section>
@endsection
