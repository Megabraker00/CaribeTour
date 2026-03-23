<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'typeable',
    ];

    // from 1 to 9 belongs to Product model
    const TOUR = 1;
    const EXCURSION = 2;
    const HOTEL = 3;
    const INSURANCE = 4;
    const CRUISE = 5;
    const FLIGHT = 6;
    const TRANSFER = 7;
    const FREETOUR = 8;

    // from 10 to 19 belongs to Payment model
    const PAID_BY_CARD = 10;
    const MONETARY_TRANSFER = 11;
    const PAID_BY_STRIPE = 12;
    const PAID_BY_PAYPAL = 13;
    const PAID_BY_CASH = 14;

    // from 20 to 29 belongs to Passenger
    const INFANT = 20;
    const CHILD = 21;
    const ADULT = 22;
    const SENIOR = 23;

    public function __toString()
    {
        return $this->name;
    }

    public function typeable()
    {
        return $this->morphTo();
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'typeable');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'typeable');
    }
}
