<?php

namespace App\Models\Concerns;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStatus
{
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Alias de status() para listados que aún eager-loadean statusRecord.
     */
    public function statusRecord(): BelongsTo
    {
        return $this->status();
    }

    public function hasStatusSlug(string ...$slugs): bool
    {
        $current = $this->relationLoaded('status')
            ? $this->status?->slug
            : Status::query()->whereKey($this->status_id)->value('slug');

        return $current !== null && in_array($current, $slugs, true);
    }

    public function scopeWhereStatusSlug(Builder $query, string $slug): Builder
    {
        return $query->whereHas('status', static function ($statusQuery) use ($slug) {
            $statusQuery->where('slug', $slug);
        });
    }
}
