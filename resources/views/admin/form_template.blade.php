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

        <!-- form -->
        <form readonly>
            <div class="card-body">
                @yield('fields')
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info">Guardar</button>
            </div>
        </form>
        <!-- /form -->

    </div>

@stop

@section('js')

    @yield('custom-js')
@endsection
