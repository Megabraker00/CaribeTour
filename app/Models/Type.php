<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLookupCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;
    use ResolvesLookupCode;

    protected $fillable = [
        'name',
        'slug',
        'typeable',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public const TOUR = 'tour';
    public const EXCURSION = 'excursion';
    public const HOTEL = 'hotel';
    public const INSURANCE = 'insurance';
    public const CRUISE = 'cruise';
    public const FLIGHT = 'flight';
    public const TRANSFER = 'transfer';
    public const FREETOUR = 'freetour';

    public const PAID_BY_CARD = 'card';
    public const MONETARY_TRANSFER = 'bank_transfer';
    public const PAID_BY_STRIPE = 'stripe';
    public const PAID_BY_PAYPAL = 'paypal';
    public const PAID_BY_CASH = 'cash';

    public const INFANT = 'infant';
    public const CHILD = 'child';
    public const ADULT = 'adult';
    public const SENIOR = 'senior';

    public function __toString()
    {
        return (string) $this->name;
    }

    protected static function ownerColumn(): string
    {
        return 'typeable';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'type_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'type_id');
    }
}
