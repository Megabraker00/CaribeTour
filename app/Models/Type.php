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
    public const TOUR = 1;
    public const EXCURSION = 2;
    public const HOTEL = 3;
    public const INSURANCE = 4;
    public const CRUISE = 5;
    public const FLIGHT = 6;
    public const TRANSFER = 7;
    public const FREETOUR = 8;

    // from 10 to 19 belongs to Payment model
    public const PAID_BY_CARD = 10;
    public const MONETARY_TRANSFER = 11;
    public const PAID_BY_STRIPE = 12;
    public const PAID_BY_PAYPAL = 13;
    public const PAID_BY_CASH = 14;

    // from 20 to 29 belongs to Passenger
    public const INFANT = 20;
    public const CHILD = 21;
    public const ADULT = 22;
    public const SENIOR = 23;

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
