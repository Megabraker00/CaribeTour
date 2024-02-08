<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function state(): MorphOne
    {
        return $this->morphOne(State::class, 'stateable');
    }

}
