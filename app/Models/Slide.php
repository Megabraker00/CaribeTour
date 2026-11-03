<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    protected $fillable = [
        'title',
        'text',
        'image_path',
        'product_id',
        'link_url',
        'sort_order',
        'is_active',
        'starts_on',
        'ends_on',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_on' => 'date',
        'ends_on' => 'date',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeVisibleNow(Builder $query): Builder
    {
        $today = now('Europe/Madrid')->toDateString();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $window) use ($today) {
                $window->whereNull('starts_on')->orWhereDate('starts_on', '<=', $today);
            })
            ->where(function (Builder $window) use ($today) {
                $window->whereNull('ends_on')->orWhereDate('ends_on', '>=', $today);
            });
    }

    /**
     * @return Collection<int, self>
     */
    public static function forHome(): Collection
    {
        return static::query()
            ->visibleNow()
            ->with(['product.type', 'product.category.parentCategory'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn (self $slide) => $slide->href() !== null)
            ->values();
    }

    public function href(): ?string
    {
        if ($this->product_id !== null) {
            $product = $this->product;
            if ($product === null || !$product->isPubliclyVisible()) {
                return null;
            }

            return $product->publicHref();
        }

        $url = trim((string) $this->link_url);

        return $url !== '' ? $url : null;
    }

    public function scheduleLabel(): string
    {
        $today = now('Europe/Madrid')->toDateString();

        if (!$this->is_active) {
            return 'Inactiva';
        }

        if ($this->starts_on !== null && $this->starts_on->toDateString() > $today) {
            return 'Programada';
        }

        if ($this->ends_on !== null && $this->ends_on->toDateString() < $today) {
            return 'Finalizada';
        }

        if ($this->product_id !== null && !$this->product?->isPubliclyVisible()) {
            return 'Producto no público';
        }

        return 'En portada';
    }
}
