<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\BelongsToStatus;

class Passenger extends Model
{
    use BelongsToStatus;
    use HasFactory;

    public const GENDER_MALE = "Male";
    public const GENDER_FEMALE = "Female";

    protected $fillable = [
        'booking_id',
        'name',
        'last_name',
        'date_of_birth',
        'dni_passport',
        'nationality',
        'gender',
        'passenger_type_id',
        'status_id',
        'price_at_booking',
        'taxes_at_booking',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function __toString()
    {
        return \ucwords($this->name ." ". $this->last_name);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'passenger_type_id');
    }

    public function meta()
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }

    public static function getPassengerTypeIdByAge(int $age)
    {
        if ($age < 2) {
            return Type::idFor(self::class, Type::INFANT);
        }
        if ($age < 12) {
            return Type::idFor(self::class, Type::CHILD);
        }
        if ($age >= 70) {
            return Type::idFor(self::class, Type::SENIOR);
        }

        return Type::idFor(self::class, Type::ADULT);
    }
}
