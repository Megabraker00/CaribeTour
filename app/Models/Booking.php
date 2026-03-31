<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_ref',
        'client_id',
        'status_id',
        'total_price',
        'currency',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    public function itineraries(): BelongsToMany
    {
        return $this->belongsToMany(Itinerary::class, 'booking_itinerary')
                    ->withPivot('itinerary_order');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    /** Estado por FK status_id (para mostrar nombre en vistas). */
    public function statusRecord(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function metaData(): MorphOne
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }

    /**
     * Contenido de meta_data (customer_notes, internal_notes, …).
     */
    public function getMetaAttribute(): array
    {
        $meta = $this->metaData?->meta_data;

        return is_array($meta) ? $meta : [];
    }
}
