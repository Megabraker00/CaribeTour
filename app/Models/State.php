<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class State extends Model
{
    use HasFactory;

    public function stateable()
    {
        return $this->morphTo();
    }

    public function employees(): MorphMany
    {
        return $this->morphMany(Employee::class, 'stateable');
    }

    public function positions(): MorphMany
    {
        return $this->morphMany(Position::class, 'stateable');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'stateable');
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'stateable');
    }

    public function clients(): MorphMany
    {
        return $this->morphMany(Client::class, 'stateable');
    }

    public function supliers(): MorphMany
    {
        return $this->morphMany(Suplier::class, 'stateable');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'stateable');
    }

    public function categories(): MorphMany
    {
        return $this->morphMany(Category::class, 'stateable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'stateable');
    }

    public function blogs(): MorphMany
    {
        return $this->morphMany(Blog::class, 'stateable');
    }
}
