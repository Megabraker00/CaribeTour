@extends('errors.http')

@section('error_title', 'Demasiados intentos')
@section('error_description', 'Has hecho demasiadas solicitudes. Espera un momento y vuelve a intentarlo.')
@section('error_code', '429')
@section('error_heading', 'Demasiados intentos')
@section('error_message')
{{ __('auth.too_many') }}
@endsection
