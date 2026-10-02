<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status_id',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }
}
