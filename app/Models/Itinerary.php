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

    protected $appends = ['days', 'nights'];

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
        return $this->belongsToMany(Booking::class, 'booking_itinerary');
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class);
    }

    public function firstSegment()
    {
        return $this->segments()->orderBy('sort_order')->first();
    }

    public function lastSegment()
    {
        return $this->segments()->orderBy('sort_order', 'desc')->first();
    }

    public function days(): int
    {
        $first = $this->firstSegment();
        $last = $this->lastSegment();

        if (!$first || !$last || !$first->departure_date || !$last->arrival_date) {
            return 0;
        }

        $start = \Carbon\Carbon::parse($first->departure_date);
        $end = \Carbon\Carbon::parse($last->arrival_date);

        // +1 porque si sale el día 1 y llega el día 3 son 3 días (1,2,3)
        return $start->diffInDays($end) + 1;
    }

    public function nights(): int
    {
        $days = $this->days();
        return max(0, $days - 1);
    }

    public function __toString()
    {
        return "Itinerario de {$this->product->name} desde {$this->departure_t->name} hasta {$this->arrival_t->name}";
    }

    public function fullPrice()
    {
        return $this->price + $this->taxes;
    }

    public function getDaysAttribute()
    {
        return $this->days();
    }

    public function getNightsAttribute()
    {
        return $this->nights();
    }
}
