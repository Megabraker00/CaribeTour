<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Type extends Model
{
    use HasFactory;

    public function typeable()
    {
        return $this->morphTo();
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'typeable');
    }

    public function clients(): MorphMany
    {
        return $this->morphMany(Client::class, 'typeable');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'typeable');
    }
}
