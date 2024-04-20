<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Type extends Model
{
    use HasFactory;

    const TOUR = 1;
    const EXCURSION = 2;
    const SEGURO = 3;
    const FREETOUR = 4;

    const CLIENT_HOLDER = 5;
    const CLIENT_PASSENGERS = 6;

    public function typeable()
    {
        return $this->morphTo();
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'typeable');
    }

    public function clients(): MorphMany
    {
        return $this->morphMany(Client::class, 'typeable');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'typeable');
    }
}
