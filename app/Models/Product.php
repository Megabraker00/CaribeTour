<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Concerns\BelongsToStatus;
use App\Models\Concerns\ReadsPlainMetaDescription;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use BelongsToStatus;
    use HasFactory;
    use ReadsPlainMetaDescription;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'type_id',
        'status_id',
        'supplier_id',
        'created_user_id',
    ];

    public function __toString()
    {
        return $this->name;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function segments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Segment::class,
            Itinerary::class,
            'product_id',
            'itinerary_id',
            'id',
            'id'
        );
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderByDesc('is_main')
            ->orderBy('id');
    }

    public function mainImage()
    {

        return $this->morphOne(Image::class, 'imageable')->where('is_main', 1) ?? new Image();

        /*
        return  $this->images()->where('is_main', 1)->first()
            ?? $this->images()->first()
            ?? new Image();
            */
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function tourSlug(): string
    {
        $ret = [
            $this->category->fullSlug(),
            $this->slug,
        ];

        return implode('/', $ret);
    }

    public function serviceSlug(): string
    {
        $ret = [
            $this->category->slug,
            $this->slug,
        ];

        return implode('/', $ret);
    }

    /**
     * Hasta cuatro productos públicos. Se usa el primer grupo que tenga resultados:
     * tours de la provincia, excursiones de la provincia, tours de otra provincia
     * del mismo país, excursiones de otras provincias del mismo país y tours de otros países.
     *
     * @return Collection<int, self>
     */
    public function relatedProducts(int $limit = 4): Collection
    {
        $provinceId = (int) $this->category_id;
        $countryId = (int) ($this->category?->parent_id ?? 0);

        $steps = [
            fn (Builder $query) => $query->publicVisibleTour()->where('category_id', $provinceId),
            fn (Builder $query) => $query->publicVisibleExcursion()->where('category_id', $provinceId),
            fn (Builder $query) => $query->publicVisibleTour()->whereHas(
                'category',
                fn (Builder $category) => $category->where('parent_id', $countryId)->whereKeyNot($provinceId)
            ),
            fn (Builder $query) => $query->publicVisibleExcursion()->whereHas(
                'category',
                fn (Builder $category) => $category->where('parent_id', $countryId)->whereKeyNot($provinceId)
            ),
            fn (Builder $query) => $query->publicVisibleTour()->whereHas(
                'category',
                fn (Builder $category) => $category->whereNotNull('parent_id')->where('parent_id', '!=', $countryId)
            ),
        ];

        foreach ($steps as $constrain) {
            $query = static::query()->whereKeyNot($this->id);
            $constrain($query);
            $found = $query
                ->with(['category.parentCategory', 'images', 'type', 'metaData'])
                ->orderBy('name')
                ->limit($limit)
                ->get();

            if ($found->isNotEmpty()) {
                return $found;
            }
        }

        return new Collection();
    }

    public function metaData()
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }

    public function getMetaAttribute()
    {
        return $this->metaData?->meta_data ?? [];
    }

    public function stars()
    {
        $stars = isset($this->meta['stars']) ? (int) $this->meta['stars'] : 0;
        $stars = max(0, min(5, $stars));
        return $stars;
    }

    /**
     * Tours activos visibles en la web: categoría y padre activos, y el primer tramo de alguna salida es de hoy o posterior.
     */
    public function scopePublicVisibleTour(Builder $query): Builder
    {
        return $query
            ->whereStatusSlug(Status::PRODUCT_ACTIVE)
            ->whereHas('type', static fn ($type) => $type->where('slug', Type::TOUR))
            ->whereHas('itineraries', function ($itineraries) {
                $itineraries->withBookableDeparture();
            })
            ->inPublicCategory();
    }

    /**
     * Excursión activa visible en la web: categoría y padre activos, y alguna salida es de hoy o posterior.
     */
    public function scopePublicVisibleExcursion(Builder $query): Builder
    {
        return $query
            ->whereStatusSlug(Status::PRODUCT_ACTIVE)
            ->whereHas('type', static fn ($type) => $type->where('slug', Type::EXCURSION))
            ->whereHas('itineraries', function ($itineraries) {
                $itineraries->withBookableDeparture();
            })
            ->inPublicCategory();
    }

    /**
     * La categoría del producto está activa y, si tiene padre, el padre también.
     */
    public function scopeInPublicCategory(Builder $query): Builder
    {
        return $query->whereHas('category', function (Builder $category) {
            $category->publiclyAvailable();
        });
    }

    public function isInPublicCategory(): bool
    {
        return static::query()->whereKey($this->id)->inPublicCategory()->exists();
    }

    /**
     * Servicio activo visible en la web: no es un tour y su categoría está publicada.
     */
    public function scopePublicVisibleService(Builder $query): Builder
    {
        return $query
            ->whereStatusSlug(Status::PRODUCT_ACTIVE)
            ->whereHas('type', static fn ($type) => $type->where('slug', '!=', Type::TOUR))
            ->inPublicCategory();
    }

    public function cheapestItinerary()
    {
        return $this->itineraries()
            ->bookableInStock()
            ->with(['segments' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderByRaw('price + taxes ASC')
            ->first();
    }

    /**
     * Menor precio por persona (precio + tasas) entre las salidas que
     * cheapestItinerary() también elegiría, agrupado por país o por provincia.
     *
     * @param  iterable<int|string>  $categoryIds
     * @return array<int, float>
     */
    public static function lowestAdvertisedPrices(iterable $categoryIds, bool $byParent = false): array
    {
        $ids = collect($categoryIds)
            ->map(static fn ($id) => (int) $id)
            ->filter(static fn (int $id) => $id > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $query = Itinerary::query()
            ->bookableInStock()
            ->whereHas('product', function (Builder $product) use ($ids, $byParent) {
                $product->publicVisibleTour();

                if ($byParent) {
                    $product->whereHas('category', function (Builder $category) use ($ids) {
                        $category->whereIn('parent_id', $ids);
                    });

                    return;
                }

                $product->whereIn('category_id', $ids);
            })
            ->join('products', 'products.id', '=', 'itineraries.product_id');

        $groupColumn = 'products.category_id';

        if ($byParent) {
            $query->join('categories', 'categories.id', '=', 'products.category_id');
            $groupColumn = 'categories.parent_id';
        }

        return $query
            ->selectRaw($groupColumn.' as price_category_id, MIN(itineraries.price + itineraries.taxes) as lowest_price')
            ->groupBy($groupColumn)
            ->get()
            ->mapWithKeys(static function ($row) {
                return [(int) $row->price_category_id => round((float) $row->lowest_price, 2)];
            })
            ->all();
    }
}
