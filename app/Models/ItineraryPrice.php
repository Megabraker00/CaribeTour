<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryPrice extends Model
{
    protected $table = 'itinerary_prices';

    protected $fillable = [
        'itinerary_id',
        'passenger_type_id',
        'price',
        'taxes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'taxes' => 'decimal:2',
    ];

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function passengerType(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'passenger_type_id');
    }
}
