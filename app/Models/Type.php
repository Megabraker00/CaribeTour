<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Type extends Model
{
    use HasFactory;

    // from 1 to 9 belongs to Product model
    const TOUR = 1;
    const EXCURSION = 2;
    const SEGURO = 3;
    const FREETOUR = 4;

    // from 10 to 19 belongs to Payment model
    const CARD = 10;
    const TRANSFER = 11;
    const STRIPE = 12;
    const PAYPAL = 13;
    const CASH = 14;

    // from 20 to 29 belongs to Client model
    const HOLDER = 20;
    const PASSENGER = 21;

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
