<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {

        $featured_products = Product::all();

        $tours = Product::select('id', 'name', 'category_id', 'slug')->where('type_id', 1)->take(8)->get();

        $blog = Blog::orderBy('id', 'DESC')->firstOrNew();

        $featured_excursions = Product::where('type_id', 2)->take(6)->get();

        return view('index', [
            'featured_products' => $featured_products,
            'tours' => $tours,
            'blog' => $blog,
            'featured_excursions' => $featured_excursions,
        ]);
    }
}
