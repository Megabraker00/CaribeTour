<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\BelongsToStatus;

class Position extends Model
{
    use BelongsToStatus;
    use HasFactory;

    protected $fillable = [
        'name',
        'status_id',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

}
