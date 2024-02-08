<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Date extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function departure_t(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'departure_terminal_id', 'id');
    }

    public function return_t(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'return_terminal_id', 'id');
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class);
    }
}
