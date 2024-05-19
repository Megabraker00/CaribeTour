<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Blog extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function state(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    public function create_date()
    {
        //return date('d-m-Y', strtotime($this->created_at));
        return $this->create_at?->format('d-m-Y');
    }
}
