<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('destination.category', ['categories' => $parentCategories]);
    }

    public function categoryIndex($slugParentCategory)
    {
        $parentCategory = Category::where('slug', $slugParentCategory)->first();

        $subCategories = [];

        if ($parentCategory) {
            $subCategories = $parentCategory->subCategories;
        }

        return view('destination.sub_category', ['subCategories' => $subCategories]);
    }

    public function subcategoryIndex($slugCategory, $slugSubCategoy)
    {
        $subCategory = Category::where('slug', $slugSubCategoy)->first();

        $tours = [];

        if ($subCategory) {
            $tours = $subCategory->products;
        }

        $categories_search = Category::whereNotNull('parent_id')->get();
        
        view()->share('categories', $categories_search);

        return view('destination.tour_list', ['tours' => $tours]);
    }

    public function tourShow($category, $subCategory, $slugTour)
    {
        $tour = Product::where('slug', $slugTour)->first();

        return view('destination.tour', ['tour' => $tour]);
    }
}
