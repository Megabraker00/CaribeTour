@extends('front_template')
@section('title', 'Destino no disponible - Especialistas en el Caribe')
@section('description', 'Este destino no está disponible en este momento. Consulta otros destinos del Caribe con CaribeTour.')

@section('content')
    <section class="container my-4">
        <x-other-destinations>
            Actualmente este destino no está disponible.
            ¡Vuelve pronto o consulta otros destinos!
        </x-other-destinations>
    </section>
@endsection
