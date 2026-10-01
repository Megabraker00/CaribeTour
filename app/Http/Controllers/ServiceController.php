<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Status;
use App\Models\Type;

class ServiceController extends Controller
{
    public function index()
    {
        return view('servicios');
    }

    public function show(string $servicio)
    {
        $service = Product::query()
            ->where('slug', $servicio)
            ->where('type_id', '!=', Type::TOUR)
            ->where('status_id', Status::PRODUCT_ACTIVE)
            ->with(['images', 'category.parentCategory', 'metaData', 'type'])
            ->firstOrFail();

        return view('servicios.show', compact('service'));
    }
}
