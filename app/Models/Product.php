<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function __toString()
    {
        return $this->name;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function suplier(): BelongsTo
    {
        return $this->belongsTo(Suplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
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

    public function meta()
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }
}
