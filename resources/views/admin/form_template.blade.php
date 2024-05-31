@extends('adminlte::page')

@section('title', 'Formulario')

@section('content_header')
    <h1>Formulario</h1>
@stop

@section('css')
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"> -->
@endsection

@section('content')

    <div class="card">

        @yield('form')

    </div>

@stop

@section('js')

    @yield('custom-js')
@endsection
