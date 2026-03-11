<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Passenger extends Model
{
    use HasFactory;

    const GENDER_MALE = "Male";
    const GENDER_FEMALE = "Female";

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
        return $this->belongsTo(Type::class);
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    public function meta()
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }

    public static function getPassengerTypeIdByAge(int $age)
    {
        if ($age < 2) return Type::INFANT; // Infante
        if ($age < 12) return Type::CHILD; // Niño
        if ($age >= 70) return Type::SENIOR; // Sénior (como definimos antes)

        return Type::ADULT; // Adulto
    }
}
