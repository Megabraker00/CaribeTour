<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

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

    public function dates(): HasMany
    {
        return $this->hasMany(Date::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function mainImage()
    {
        return  $this->images()->where('is_main', 1)->first()
            ?? $this->images()->first()
            ?? new Image();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
