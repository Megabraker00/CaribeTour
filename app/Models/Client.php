<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Client extends Model
{
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

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    /** Estado por FK status_id (para mostrar nombre en vistas). */
    public function statusRecord(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function meta()
    {
        return $this->morphOne(Metadata::class, 'meta_dataable');
    }
}
