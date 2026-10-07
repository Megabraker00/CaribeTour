<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Concerns\BelongsToStatus;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use BelongsToStatus;
    use HasFactory;

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
            ->whereStatusSlug(Status::PRODUCT_ACTIVE)
            ->whereHas('type', static fn ($type) => $type->where('slug', Type::TOUR))
            ->whereHas('itineraries.segments', function ($q) {
                $q->where('departure_date', '>', now());
            });
    }

    public function cheapestItinerary()
    {
        return $this->itineraries()
            ->where('available_stock', '>', 0)
            ->whereHas('segments', function ($q) {
                $q->where('departure_date', '>=', now()->startOfDay());
            })
            ->with(['segments' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderByRaw('price + taxes ASC')
            ->first();
    }
}
