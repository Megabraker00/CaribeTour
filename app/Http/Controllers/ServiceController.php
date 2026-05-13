<?php

namespace App\Http\Controllers;

class ServiceController extends Controller
{
    public function index()
    {
        return view('servicios');
    }

    /**
     * TODO: Pendiente de completar
     */
    public function show($service)
    {
        return view('servicios');
    }
}
