@extends('front_template')

@section('http_error', '1')
@section('robots', 'noindex, nofollow')

@section('title')
@yield('error_title')
@endsection

@section('description')
@yield('error_description')
@endsection

@section('head')
    <style>
        .http-error-code {
            color: #ff9000;
            font-size: 8rem;
            font-weight: 600;
            line-height: 0.85;
        }
    </style>
@endsection

@section('content')
    <section class="container my-5">
        <div class="text-center col-lg-8 mx-auto">
            <p class="http-error-code mb-3">@yield('error_code')</p>
            <h1 class="mb-3">@yield('error_heading')</h1>
            <p class="mb-4">@yield('error_message')</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('inicio') }}" class="btn btn-primary">Ir al inicio</a>
                <a href="{{ route('destinos') }}" class="btn btn-outline-secondary">Ver destinos</a>
            </div>
        </div>
    </section>
@endsection
