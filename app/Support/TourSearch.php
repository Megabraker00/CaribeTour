<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Terminal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class TourSearch
{
    /**
     * Provincias con algún tour visible, agrupadas por el nombre del país.
     *
     * @return Collection<string, Collection<int, Category>>
     */
    public static function provinces(): Collection
    {
        return Category::query()
            ->publiclyAvailable()
            ->whereNotNull('parent_id')
            ->whereHas('products', function (Builder $products) {
                $products->publicVisibleTour()
                    ->whereHas('itineraries', function (Builder $itineraries) {
                        $itineraries->forSearch(null, null);
                    });
            })
            ->with('parentCategory')
            ->get()
            ->sortBy(fn (Category $province) => $province->parentCategory->name.' '.$province->name)
            ->groupBy(fn (Category $province) => $province->parentCategory->name);
    }

    /**
     * Terminales del primer tramo de una salida con plazas.
     *
     * @return array{loose: Collection<int, Terminal>, groups: Collection<string, Collection<int, Terminal>>}
     */
    public static function origins(): array
    {
        $terminals = Terminal::query()
            ->with('parentTerminal')
            ->whereIn('id', self::openingTerminalIds())
            ->orderBy('name')
            ->get();

        return [
            'loose' => $terminals
                ->filter(fn (Terminal $terminal) => $terminal->parentTerminal === null)
                ->values(),
            'groups' => $terminals
                ->filter(fn (Terminal $terminal) => $terminal->parentTerminal !== null)
                ->groupBy(fn (Terminal $terminal) => $terminal->parentTerminal->name)
                ->sortKeys(),
        ];
    }

    public static function province(mixed $value): ?Category
    {
        if (!is_string($value) || !str_contains($value, '/')) {
            return null;
        }

        [$countrySlug, $provinceSlug] = explode('/', $value, 2);
        if ($countrySlug === '' || $provinceSlug === '' || str_contains($provinceSlug, '/')) {
            return null;
        }

        $country = Category::query()
            ->publiclyAvailable()
            ->whereNull('parent_id')
            ->where('slug', $countrySlug)
            ->first();

        if (!$country) {
            return null;
        }

        return Category::query()
            ->publiclyAvailable()
            ->where('parent_id', $country->id)
            ->where('slug', $provinceSlug)
            ->first();
    }

    public static function terminal(mixed $value): ?Terminal
    {
        if (!is_string($value) || !ctype_digit($value)) {
            return null;
        }

        return Terminal::query()->find((int) $value);
    }

    /**
     * Fecha de salida a partir de hoy. Una fecha inválida o pasada no filtra.
     */
    public static function date(mixed $value): ?Carbon
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        $date = Carbon::createFromFormat('Y-m-d', $value)?->startOfDay();
        if (!$date || $date->format('Y-m-d') !== $value || $date->lt(now()->startOfDay())) {
            return null;
        }

        return $date;
    }

    public static function products(?Category $province, ?Terminal $terminal, ?Carbon $from): Builder
    {
        return Product::query()
            ->publicVisibleTour()
            ->when($province, fn (Builder $query) => $query->where('category_id', $province->id))
            ->whereHas('itineraries', function (Builder $itineraries) use ($terminal, $from) {
                $itineraries->forSearch($terminal?->id, $from);
            })
            ->with([
                'images',
                'category.parentCategory',
                'itineraries' => function ($itineraries) use ($terminal, $from) {
                    $itineraries->forSearch($terminal?->id, $from)
                        ->with(['segments' => function ($segments) {
                            $segments->orderBy('sort_order')->orderBy('id');
                        }])
                        ->orderByRaw('price + taxes asc')
                        ->orderBy('itineraries.id');
                },
            ])
            ->orderBy('name');
    }

    /**
     * @return Builder<Segment>
     */
    private static function openingTerminalIds(): Builder
    {
        return Segment::query()
            ->select('departure_terminal_id')
            ->whereNotNull('departure_terminal_id')
            ->whereRaw(
                'sort_order = (
                    select min(earliest.sort_order)
                    from segments as earliest
                    where earliest.itinerary_id = segments.itinerary_id
                )'
            )
            ->whereIn('itinerary_id', Itinerary::query()
                ->select('itineraries.id')
                ->bookableInStock()
                ->whereHas('product', function (Builder $product) {
                    $product->publicVisibleTour();
                }));
    }
}
