@extends('errors.http')

@section('error_title', 'Sesión caducada')
@section('error_description', 'El formulario ha caducado. Vuelve a cargarlo y envíalo de nuevo.')
@section('error_code', '419')
@section('error_heading', 'La sesión ha caducado')
@section('error_message', 'El formulario se quedó abierto demasiado tiempo. Vuelve a cargar la página y envíalo otra vez.')
