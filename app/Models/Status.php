<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Status extends Model
{
    use HasFactory;

    const BOOKING_PENDING_PAYMENT = 1;
    const BOOKING_PAID = 2;
    const BOOKING_CANCELLED = 3;

    const CLIENT_ACTIVE = 4;

    const TOUR_DRAFT = 5;
    const TOUR_ACTIVE = 6;
    const TOUR_NOT_ACTIVE = 7;

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
