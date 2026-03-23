<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read array $meta Contenido de meta_data (p. ej. description).
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'status_id',
    ];

    public function __toString()
    {
        return $this->name;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    /** Estado por FK status_id (listados, admin). */
    public function statusRecord(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function fullSlug(): string
    {
        $ret = [
            $this->parentCategory?->slug,
            $this->slug,
        ];

        return implode('/', array_filter($ret));
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function metaData(): MorphOne
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }

    public function getMetaAttribute(): array
    {
        $meta = $this->metaData?->meta_data;

        return is_array($meta) ? $meta : [];
    }

    public function mainImage()
    {
        return $this->images()->where('is_main', 1)->first()
            ?? $this->images()->first()
            ?? new Image();
    }
}
