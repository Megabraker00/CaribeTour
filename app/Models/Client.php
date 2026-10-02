<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\BelongsToStatus;

class Client extends Model
{
    use BelongsToStatus;
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'phone',
        'email',
        'date_of_birth',
        'dni_passport',
        'nationality',
        'status_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function __toString()
    {
        return \ucwords($this->name ." ". $this->last_name);
    }

    /** Reservas de las que este cliente es titular. */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function meta()
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }
}
