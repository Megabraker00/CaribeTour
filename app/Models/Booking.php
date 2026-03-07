<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Booking extends Model
{
    use HasFactory;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }
}
