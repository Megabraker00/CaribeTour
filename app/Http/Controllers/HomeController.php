<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        $cheapest = DB::table('itineraries')
            ->selectRaw('product_id, MIN(price + taxes) as total')
            ->groupBy('product_id');

        $featured_products = Product::query()
            ->with(['category.parentCategory'])
            ->joinSub($cheapest, 'cheapest', function ($join) {
                $join->on('products.id', '=', 'cheapest.product_id');
            })
            ->where('products.status_id', Status::PRODUCT_ACTIVE)
            ->whereHas('itineraries.segments', function ($q) {
                $q->where('departure_date', '>', now());
            })
            ->orderBy('cheapest.total')
            ->select('products.*', 'cheapest.total')
            ->take(5)
            ->get();

        $tours = Product::query()
            ->publicVisibleTour()
            ->with(['category.parentCategory', 'mainImage'])
            ->take(8)
            ->get();

        $blog = Blog::where('status_id', Status::BLOG_PUBLISHED)
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
