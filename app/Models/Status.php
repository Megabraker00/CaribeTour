<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Status extends Model
{
    use HasFactory;

    // from 1 to 9 belongs to Product model
    const TOUR_ACTIVE = 1;
    const TOUR_NOT_ACTIVE = 2;
    const TOUR_DRAFT = 3;

    // from 10 to 19 belongs to Booking model
    const BOOKING_PENDING_PAYMENT = 10;
    const BOOKING_PAID = 11;
    const BOOKING_PENDING = 12;
    const BOOKING_CONFIRMED = 13;
    const BOOKING_COMPLETED = 14;
    const BOOKING_CANCELLED = 15;
    const BOOKING_REFUNDED = 16;
    const BOOKING_NO_SHOW = 17;

    // from 20 to 29 belongs to Client model
    const CLIENT_ACTIVE = 20;

    // from 30 to 39 belongs to Category model
    const CATEGORY_ACTIVE = 30;
    const CATEGORY_INACTIVE = 31;

    // from 40 to 49 belongs to Supplier model
    const SUPPLIER_ACTIVE = 40;
    const SUPPLIER_INACTIVE = 41;

    // from 50 to 59 belongs to Payment model
    public const PAYMENT_PENDING   = 50;
    public const PAYMENT_PAID      = 51;
    public const PAYMENT_CANCELLED = 52;

    // from 60 to 69 belongs to Blog model
    public const BLOG_PUBLISHED = 60;
    public const BLOG_DRAFT = 61;

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

    public function suppliers(): MorphMany
    {
        return $this->morphMany(Supplier::class, 'statusable');
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
