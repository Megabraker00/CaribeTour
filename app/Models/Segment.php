<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'type_id',
        'sort_order',
        'departure_date',
        'departure_terminal_id',
        'origin',
        'arrival_date',
        'arrival_terminal_id',
        'destination',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function departureTerminal()
    {
        return $this->belongsTo(Terminal::class, 'departure_terminal_id');
    }

    public function arrivalTerminal()
    {
        return $this->belongsTo(Terminal::class, 'arrival_terminal_id');
    }
}
