<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function countryIndex()
    {
        $parentCategories = Category::whereNull('parent_id')->get();

        //return view('destination.category', ['categories' => $parentCategories]);
        return view('destinos', ['categories' => $parentCategories]);
    }

    public function provinceIndex($slugParentCategory)
    {
        $parentCategory = Category::where('slug', $slugParentCategory)->first();

        $subCategories = [];

        if ($parentCategory) {
            $subCategories = $parentCategory->subCategories;
        }

        return view('destination.sub_category', ['subCategories' => $subCategories]);
    }

    public function tourIndex($slugCategory, $slugSubCategoy)
    {
        $subCategory = Category::where('slug', $slugSubCategoy)->first();

        $tours = [];

        if ($subCategory) {
            $tours = $subCategory->products()->paginate(4);
        }

        $categories_search = Category::whereNotNull('parent_id')->get();
        
        view()->share('categories', $categories_search);

        return view('destination.tour_list', ['tours' => $tours]);
    }

    public function tourShow($category, $subCategory, $slugTour)
    {
        $tour = Product::where('slug', $slugTour)->first();

        $firstDate = $tour->itineraries->sortBy('id')->first();
        $price = $firstDate->price + $firstDate->taxes; // blade $tour->itineraries->sortBy('id')->first()->price

        return view('destination.tour', ['tour' => $tour, 'firstDate' => $firstDate, 'price' => $price]);
    }
}
