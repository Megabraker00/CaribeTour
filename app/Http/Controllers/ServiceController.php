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
            ->whereHas('type', static fn ($type) => $type->where('slug', '!=', Type::TOUR))
            ->first();

        if ($service === null) {
            abort(404);
        }

        $visible = Product::query()
            ->whereKey($service->id)
            ->whereStatusSlug(Status::PRODUCT_ACTIVE)
            ->inPublicCategory()
            ->exists();

        if (!$visible) {
            return view('unavailable');
        }

        $service->load(['images', 'category.parentCategory', 'metaData', 'type']);

        return view('servicios.show', compact('service'));
    }
}
