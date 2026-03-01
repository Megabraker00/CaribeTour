<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itinerary extends Model
{
    use HasFactory;

    protected $table = 'itineraries';

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function departure_t(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'departure_terminal_id', 'id');
    }

    public function arrival_t(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'arrival_terminal_id', 'id');
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class)->orderBy('sort_order');
    }

    public function __toString()
    {
        return "Itinerario de {$this->product->name} desde {$this->departure_t->name} hasta {$this->arrival_t->name}";
    }
}
