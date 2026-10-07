<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Itinerary extends Model
{
    use HasFactory;

    protected $table = 'itineraries';

    protected $fillable = [
        'product_id',
        'total_stock',
        'available_stock',
        'price',
        'taxes',
        'currency',
    ];

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

    public function itineraryPrices(): HasMany
    {
        return $this->hasMany(ItineraryPrice::class);
    }

    public function firstSegment()
    {
        return $this->segments()->orderBy('sort_order')->first();
    }

    public function lastSegment()
    {
        return $this->segments()->orderBy('sort_order', 'desc')->first();
    }

    /**
     * Tramos que aún se pueden reservar, del más próximo al último.
     * Un tramo anterior o de conexión no cuenta como otra salida.
     */
    public function upcomingSegments(): Collection
    {
        $today = now()->startOfDay();

        return $this->segmentsOrderedByDeparture()
            ->filter(function (Segment $segment) use ($today) {
                return $segment->departure_date->greaterThanOrEqualTo($today);
            })
            ->values();
    }

    /**
     * Fechas del resumen: la salida reservable y el último tramo que sale ese día o después.
     *
     * @return array{departure: mixed, return: mixed, days: int, nights: int}
     */
    public function reservableSummary(): array
    {
        $segments = $this->upcomingSegments();
        if ($segments->isEmpty()) {
            $segments = $this->segmentsOrderedByDeparture();
        }

        $first = $segments->first();
        $last = $segments->last();
        $departure = $first?->departure_date;
        $end = $last?->arrival_date ?? $last?->departure_date;
        $days = 0;

        if ($departure && $end) {
            $days = (int) $departure->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) + 1;
        }

        return [
            'departure' => $departure,
            'return' => $last?->departure_date,
            'days' => $days,
            'nights' => max(0, $days - 1),
        ];
    }

    private function segmentsOrderedByDeparture(): Collection
    {
        $segments = $this->relationLoaded('segments')
            ? $this->segments
            : $this->segments()->get();

        return $segments
            ->filter(fn (Segment $segment) => $segment->departure_date !== null)
            ->sortBy(fn (Segment $segment) => $segment->departure_date->getTimestamp())
            ->values();
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
        return round($this->price + $this->taxes, 2);
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
