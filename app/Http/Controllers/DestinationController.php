<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class DestinationController extends Controller
{
    public function countryIndex()
    {
        $parentCategories = Category::query()
            ->whereNull('parent_id')
            ->whereHas('subCategories.products', function ($q) {
                $q->publicVisibleTour();
            })
            ->with(['images'])
            ->get();

        return view('destination.destinos', ['categories' => $parentCategories]);
    }

    public function countryShow(string $country)
    {
        $parentCategory = Category::query()
            ->where('slug', $country)
            ->whereNull('parent_id')
            ->with(['images', 'metaData'])
            ->first();

        if (!$parentCategory) {
            abort(404);
        }

        $subCategories = $parentCategory
            ->subCategories()
            ->whereHas('products', function ($q) {
                $q->publicVisibleTour();
            })
            ->with(['images'])
            ->orderBy('name')
            ->get();

        return view('destination.pais', ['category' => $parentCategory, 'subCategories' => $subCategories]);
    }

    public function provinceShow(string $country, string $province)
    {
        $parentCategory = Category::query()
            ->where('slug', $country)
            ->whereNull('parent_id')
            ->first();

        if (! $parentCategory) {
            abort(404);
        }

        $subCategory = Category::query()
            ->with(['parentCategory', 'images', 'metaData'])
            ->where('slug', $province)
            ->where('parent_id', $parentCategory->id)
            ->first();

        if (!$subCategory) {
            abort(404);
        }

        $tours = $subCategory->products()
            ->publicVisibleTour()
            ->with(['mainImage', 'category.parentCategory'])
            ->paginate(4);

        $categories_search = Category::whereNotNull('parent_id')->get();

        view()->share('categories', $categories_search);

        return view('destination.provincia', [
            'countrySlug' => $country,
            'province' => $subCategory,
            'tours' => $tours,
        ]);
    }

    public function tourShow(string $country, string $province, string $tour)
    {
        $parentCategory = Category::query()
            ->where('slug', $country)
            ->whereNull('parent_id')
            ->first();

        if (! $parentCategory) {
            abort(404);
        }

        $subCategory = Category::query()
            ->where('slug', $province)
            ->where('parent_id', $parentCategory->id)
            ->first();

        if (! $subCategory) {
            abort(404);
        }

        $tourModel = Product::query()
            ->where('slug', $tour)
            ->where('category_id', $subCategory->id)
            ->publicVisibleTour()
            ->with(['metaData', 'images'])
            ->with(['itineraries' => function ($query) {
                $query->whereHas('segments', function ($q) {
                    $q->where('departure_date', '>', now());
                })
                    ->with(['segments' => function ($q) {
                        $q->orderBy('sort_order', 'asc');
                    }]);
            }])
            ->first();

        if ($tourModel === null) {
            abort(404);
        }

        $firstItinerary = $tourModel->cheapestItinerary();

        $price = $firstItinerary
            ? $firstItinerary->fullPrice()
            : null;

        $days = $firstItinerary?->days;
        $nights = $firstItinerary?->nights;

        $firstSeg = $firstItinerary?->firstSegment();
        $lastSeg = $firstItinerary?->lastSegment();
        $departure = $firstSeg?->departure_date;
        $return = $lastSeg?->departure_date;
            
            
        //$price = number_format($firstDate->price + $firstDate->taxes, 2, ',', '.');

        //$price = number_format($firstDate->price + $firstDate->taxes, 2, ',', '.');
        return view('destination.tour', [
            'tour' => $tourModel,
            'firstDate' => $firstItinerary,
            'tourDeparture' => $departure,
            'tourReturn' => $return,
            'days' => $days,
            'nights' => $nights,
            'price' => $price,
            'countrySlug' => $country,
            'provinceSlug' => $province,
        ]);
    }

    /**
     * Ruta reservada en web.php; implementar búsqueda de destinos cuando corresponda.
     */
    public function searchResult()
    {
        abort(404);
    }
}
