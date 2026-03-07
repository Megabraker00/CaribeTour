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
}
