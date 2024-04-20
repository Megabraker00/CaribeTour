<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    public function index()
    {
        return view('service');
    }

    /**
     * TODO: Pendiente de completar
     */
    public function show($service)
    {
        return view('service');
    }
}
