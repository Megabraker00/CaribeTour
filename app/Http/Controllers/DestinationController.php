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
            ->publiclyAvailable()
            ->whereHas('subCategories.products', function ($products) {
                $products->publicVisibleTour();
            })
            ->with(['images'])
            ->get();

        return view('destination.destinos', [
            'categories' => $parentCategories,
            'prices' => $this->formattedLowestPrices($parentCategories->pluck('id'), true),
        ]);
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

        if (!$parentCategory->isPubliclyAvailable()) {
            return view('unavailable');
        }

        $subCategories = $parentCategory
            ->subCategories()
            ->publiclyAvailable()
            ->whereHas('products', function ($q) {
                $q->publicVisibleTour();
            })
            ->with(['images'])
            ->orderBy('name')
            ->get();

        return view('destination.pais', [
            'category' => $parentCategory,
            'subCategories' => $subCategories,
            'prices' => $this->formattedLowestPrices($subCategories->pluck('id')),
        ]);
    }

    public function provinceShow(string $country, string $province)
    {
        $parentCategory = Category::query()
            ->where('slug', $country)
            ->whereNull('parent_id')
            ->first();

        if (!$parentCategory) {
            abort(404);
        }

        if (!$parentCategory->isPubliclyAvailable()) {
            return view('unavailable');
        }

        $subCategory = Category::query()
            ->with(['parentCategory', 'metaData'])
            ->where('slug', $province)
            ->where('parent_id', $parentCategory->id)
            ->first();

        if (!$subCategory) {
            abort(404);
        }

        if (!$subCategory->isPubliclyAvailable()) {
            return view('unavailable');
        }

        $tours = $subCategory->products()
            ->publicVisibleTour()
            ->with(['mainImage', 'category.parentCategory'])
            ->paginate(4);

        $categories_search = Category::query()->publiclyAvailable()->whereNotNull('parent_id')->get();

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

        if (!$parentCategory) {
            abort(404);
        }

        if (!$parentCategory->isPubliclyAvailable()) {
            return view('unavailable');
        }

        $subCategory = Category::query()
            ->where('slug', $province)
            ->where('parent_id', $parentCategory->id)
            ->first();

        if (!$subCategory) {
            abort(404);
        }

        if (!$subCategory->isPubliclyAvailable()) {
            return view('unavailable');
        }

        $tourModel = Product::query()
            ->where('slug', $tour)
            ->where('category_id', $subCategory->id)
            ->with(['metaData', 'images'])
            ->with(['itineraries' => function ($query) {
                $query->where('available_stock', '>', 0)
                    ->withBookableDeparture()
                    ->with(['segments' => function ($q) {
                        $q->orderBy('sort_order', 'asc');
                    }]);
            }])
            ->first();

        if ($tourModel === null) {
            abort(404);
        }

        if (!Product::query()->whereKey($tourModel->id)->publicVisibleTour()->exists()) {
            return view('unavailable');
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
     * @param  iterable<int|string>  $categoryIds
     * @return array<int, string>
     */
    private function formattedLowestPrices(iterable $categoryIds, bool $byParent = false): array
    {
        $formatted = [];

        foreach (Product::lowestAdvertisedPrices($categoryIds, $byParent) as $categoryId => $price) {
            $formatted[$categoryId] = number_format($price, 2, ',', '.');
        }

        return $formatted;
    }

    /**
     * Ruta reservada en web.php; implementar búsqueda de destinos cuando corresponda.
     */
    public function searchResult()
    {
        abort(404);
    }
}
