<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featured_products = Product::select('products.*', DB::raw('SUM(dates.price + dates.taxes) as total'))
            ->join('dates', 'products.id', '=', 'dates.product_id')
            ->groupBy('products.id')
            ->orderBy('total')
            ->take(5)
            ->get();

        // TODO: hacer la consultas para productos que tengan fechas disponibles y la categoría y el producto estén activos
        $tours = Product::select('id', 'name', 'category_id', 'slug')
            ->with(['category', 'mainImage'])
            ->where('type_id', Type::TOUR)->take(8)->get();

        // TODO: hacer la consulta para blogs que esté publicados (no borrador)
        $blog = Blog::orderBy('id', 'DESC')->firstOrNew();

        $featured_excursions = Product::with('category')->where('type_id', Type::EXCURSION)->take(6)->get();

        $categories_search = Category::whereNotNull('parent_id')->get();

        view()->share('categories', $categories_search);

        return view('home', [
            'featured_products' => $featured_products,
            'tours' => $tours,
            'blog' => $blog,
            'featured_excursions' => $featured_excursions,
        ]);
    }
}
