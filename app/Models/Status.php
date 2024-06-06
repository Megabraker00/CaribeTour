<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Status extends Model
{
    use HasFactory;

    // from 1 to 9 belongs to Product model
    const TOUR_DRAFT = 1;
    const TOUR_ACTIVE = 2;
    const TOUR_NOT_ACTIVE = 3;

    // from 10 to 19 belongs to Booking model
    const BOOKING_PENDING_PAYMENT = 10;
    const BOOKING_PAID = 11;
    const BOOKING_CANCELLED = 12;

    // from 20 to 29 belongs to Client model
    const CLIENT_ACTIVE = 20;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statuses';

    public function statusable()
    {
        return $this->morphTo();
    }

    public function employees(): MorphMany
    {
        return $this->morphMany(Employee::class, 'statusable');
    }

    public function positions(): MorphMany
    {
        return $this->morphMany(Position::class, 'statusable');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'statusable');
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'statusable');
    }

    public function clients(): MorphMany
    {
        return $this->morphMany(Client::class, 'statusable');
    }

    public function supliers(): MorphMany
    {
        return $this->morphMany(Suplier::class, 'statusable');
    }

    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'statusable');
    }

    public function categories(): MorphMany
    {
        return $this->morphMany(Category::class, 'statusable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'statusable');
    }

    public function blogs(): MorphMany
    {
        return $this->morphMany(Blog::class, 'statusable');
    }
}
