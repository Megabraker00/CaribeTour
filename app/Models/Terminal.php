<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'parent_id',
    ];

    public function parentTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Terminal::class, 'parent_id');
    }

    public function departure_dates(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'departure_terminal_id', 'id');
    }

    public function return_dates(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'return_terminal_id', 'id');
    }
}
