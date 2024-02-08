<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terminal extends Model
{
    use HasFactory;

    public function departure_dates(): HasMany
    {
        return $this->hasMany(Date::class, 'departure_terminal_id', 'id');
    }

    public function return_dates(): HasMany
    {
        return $this->hasMany(Date::class, 'return_terminal_id', 'id');
    }
}
