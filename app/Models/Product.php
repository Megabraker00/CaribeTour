<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_TOUR = 1;
    const TYPE_SERVICE = 2;
    const STATUS_AVAILABLE = 1;
    const STATUS_UNAVAILABLE = 2;

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

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
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

    public function related()
    {
        $productRelated = Product::where('category_id', $this->category->id)
            ->where('id', '!=', $this->id)
            ->limit(4)
            ->get();

        return $productRelated;
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
     * Tours activos visibles en la web: al menos un segmento con salida estrictamente futura.
     */
    public function scopePublicVisibleTour(Builder $query): Builder
    {
        return $query
            ->where('status_id', Status::PRODUCT_ACTIVE)
            ->where('type_id', Type::TOUR)
            ->whereHas('itineraries.segments', function ($q) {
                $q->where('departure_date', '>', now());
            });
    }

    public function cheapestItinerary()
    {
        return $this->itineraries()
            ->whereHas('segments', function ($q) {
                $q->where('departure_date', '>', now());
            })
            ->with(['segments' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderByRaw('price + taxes ASC')
            ->first();
    }
}
