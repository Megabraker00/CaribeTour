<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function countryIndex()
    {
        $parentCategories = Category::whereNull('parent_id')->get();

        //TODO: obtener el precio mínimo de cada categoría para mostrarlo en la vista
        return view('destination.destinos', ['categories' => $parentCategories]);
    }

    public function countryShow($slugParentCategory)
    {
        $parentCategory = Category::where('slug', $slugParentCategory)->first();

        if (!$parentCategory) {
            abort(404);
        }

        $subCategories = $parentCategory->subCategories;
        
        return view('destination.pais', ['category' => $parentCategory, 'subCategories' => $subCategories]);
    }

    public function provinceShow($slugCategory, $slugSubCategoy)
    {
        $subCategory = Category::with('parentCategory')->
                        where('slug', $slugSubCategoy)->first();

        if (!$subCategory) {
            abort(404);
        }

        $tours = $subCategory->products()
            ->where('status_id', Status::PRODUCT_ACTIVE)
            ->where('type_id', Type::TOUR)
            ->paginate(4);

        $categories_search = Category::whereNotNull('parent_id')->get();

        view()->share('categories', $categories_search);

        return view('destination.provincia', ['countrySlug' => $slugCategory, 'province' => $subCategory, 'tours' => $tours]);
    }

    public function tourShow($category, $subCategory, $slugTour)
    {
        //$tour = Product::where('slug', $slugTour)->first();

        
        $tour = Product::query()
                ->where('slug', $slugTour)
                ->where('status_id', Status::PRODUCT_ACTIVE)
                ->where('type_id', Type::TOUR)
                ->with('metaData')
                ->with(['itineraries' => function ($query) {
                    $query->whereHas('segments', function ($q) {
                        $q->where('departure_date', '>', now());
                    })
                    ->with(['segments' => function ($q) {
                        $q->orderBy('sort_order', 'asc');
                    }]);
                }])
                ->first();

        // Esto imprime la consulta con "?"
        //dd($tour->toSql(), $tour->getBindings());
/*
        // Crear un nuevo tour con metadata de ejemplo
        $tour->metaData()->create([
            'meta_data' => [
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'includes' => [
                    'Vuelos de ida y vuelta',
                    'Traslados aeropuerto - hotel - aeropuerto',
                    'Alojamiento en hotel 5 estrellas',
                    'Régimen de todo incluido',
                    'Seguro de viaje',
                ],
                'itinerary' => [
                    [
                        'day' => 1,
                        'activities' => [
                            'Llegada al aeropuerto y traslado al hotel',
                            'Check-in en el hotel y tiempo libre para descansar',
                            'Cena de bienvenida en el hotel'],
                    ],
                    [
                        'day' => 2,
                        'activities' => [
                            'Desayuno en el hotel',
                            'Excursión a la playa de Punta Cana',
                            'Almuerzo en un restaurante local',
                            'Tarde libre para disfrutar de la playa',
                            'Cena en el hotel'],
                    ],
                    [
                        'day' => 3,
                        'activities' => [
                            'Desayuno en el hotel',
                            'Visita a la isla Saona',
                            'Almuerzo en la isla',
                            'Tarde libre para disfrutar de la isla',
                            'Cena en el hotel'],
                    ],
                ],
                'seo' => [
                    'title' => 'Tour en Punta Cana - Caribe Tour',
                    'description' => 'Disfruta de un tour inolvidable en Punta Cana con Caribe Tour. Vuelos, alojamiento, excursiones y más incluido.',
                    'keywords' => 'tour punta cana, viaje punta cana, vacaciones punta cana, caribe tour',
                ],
            ]
        ]);
        */

        //dd($slugTour);

        if ($tour === null) {
            abort(404);
        }

        $firstItinerary = $tour->cheapestItinerary();
        
        $price = $firstItinerary
            ? $firstItinerary->fullPrice()
            : null;
        
        $days = $firstItinerary?->days;
        $nights = $firstItinerary?->nights;
        $departure = $firstItinerary->firstSegment()->departure_date;
        $return = $firstItinerary->lastSegment()->departure_date;
    
            
        //$price = number_format($firstDate->price + $firstDate->taxes, 2, ',', '.');
        return view('destination.tour', [
            'tour' => $tour, 
            'firstDate' => $firstItinerary,
            'tourDeparture' => $departure,
            'tourReturn' => $return,
            'days' => $days,
            'nights' => $nights,
            'price' => $price, 
            'countrySlug' => $category, 
            'provinceSlug' => $subCategory,
        ]);
    }
}
