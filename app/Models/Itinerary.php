<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Inicio del viaje: el tramo de menor orden. El resto pertenece a la misma salida.
     */
    public function openingSegment(): ?Segment
    {
        $segments = $this->relationLoaded('segments')
            ? $this->segments
            : $this->segments()->get();

        return $segments
            ->sortBy([
                ['sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->first();
    }

    public function closingSegment(): ?Segment
    {
        $segments = $this->relationLoaded('segments')
            ? $this->segments
            : $this->segments()->get();

        return $segments
            ->sortBy([
                ['sort_order', 'desc'],
                ['id', 'desc'],
            ])
            ->first();
    }

    /**
     * La salida se ofrece si el primer tramo sale hoy o más adelante.
     */
    public function hasBookableDeparture(): bool
    {
        $departure = $this->openingSegment()?->departure_date;

        return $departure !== null && $departure->greaterThanOrEqualTo(now()->startOfDay());
    }

    public function scopeWithBookableDeparture(Builder $query): Builder
    {
        $today = now()->startOfDay();

        return $query->whereExists(function ($subquery) use ($today) {
            $subquery->selectRaw('1')
                ->from('segments as opening')
                ->whereColumn('opening.itinerary_id', 'itineraries.id')
                ->where('opening.departure_date', '>=', $today)
                ->whereRaw(
                    'opening.sort_order = (
                        select min(earliest.sort_order)
                        from segments as earliest
                        where earliest.itinerary_id = opening.itinerary_id
                    )'
                );
        });
    }

    /**
     * Fechas del viaje completo, desde el primer tramo hasta el último.
     *
     * @return array{departure: mixed, return: mixed, days: int, nights: int}
     */
    public function reservableSummary(): array
    {
        $first = $this->openingSegment();
        $last = $this->closingSegment();

        return [
            'departure' => $first?->departure_date,
            'return' => $last?->departure_date,
            'days' => $this->days(),
            'nights' => $this->nights(),
        ];
    }

    /**
     * Días de calendario, ambos inclusive, del primer tramo al último.
     * Salir el día 1 y llegar el día 3 son 3 días, sea cual sea la hora.
     */
    public function days(): int
    {
        $start = $this->openingSegment()?->departure_date;
        $end = $this->closingSegment()?->arrival_date;

        if (!$start || !$end) {
            return 0;
        }

        $calendarDays = (int) $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay());

        return $calendarDays + 1;
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

    public function soldSeats(): int
    {
        return max(0, (int) $this->total_stock - (int) $this->available_stock);
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
