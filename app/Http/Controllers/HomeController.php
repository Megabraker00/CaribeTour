<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featured_products = Product::with(['category.parentCategory'])
        ->select('products.*')
        ->selectRaw('SUM(itineraries.price + itineraries.taxes) as total')
        ->join('itineraries', 'products.id', '=', 'itineraries.product_id')
        ->where('status_id', Status::PRODUCT_ACTIVE) // Solo activos
        ->groupBy('products.id')
        ->orderBy('total')
        ->take(5)
        ->get();

        // TODO: hacer la consultas para productos que tengan fechas disponibles y la categoría y el producto estén activos
        $tours = Product::with(['category.parentCategory', 'mainImage'])
        ->where('type_id', Type::TOUR)
        ->where('status_id', Status::PRODUCT_ACTIVE)
        ->whereHas('itineraries.segments', function($q) {
            $q->where('departure_date', '>', now());
        })
        ->take(8)
        ->get();

        // TODO: hacer la consulta para blogs que esté publicados (no borrador)
        $blog = Blog::where('status_id', \App\Models\Status::BLOG_PUBLISHED) 
        ->orderBy('id', 'DESC')
        ->first() ?? new Blog();

       $featured_excursions = Product::with('category.parentCategory')
        ->where('type_id', Type::EXCURSION)
        ->where('status_id', Status::PRODUCT_ACTIVE)
        ->take(6)
        ->get();

        $categories_search = Category::whereNotNull('parent_id')->get();

        view()->share('categories', $categories_search);

        return view('index', compact('featured_products', 'tours', 'blog', 'featured_excursions'));
    }
}
