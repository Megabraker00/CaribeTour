<?php

namespace App\Models;

use App\Models\Concerns\ResolvesLookupCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;
    use ResolvesLookupCode;

    protected $fillable = [
        'name',
        'slug',
        'statusable',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public const PRODUCT_ACTIVE = 'active';
    public const PRODUCT_NOT_ACTIVE = 'inactive';
    public const PRODUCT_DRAFT = 'draft';

    public const BOOKING_PENDING_PAYMENT = 'pending_payment';
    public const BOOKING_PAID = 'paid';
    public const BOOKING_PENDING = 'pending';
    public const BOOKING_CONFIRMED = 'confirmed';
    public const BOOKING_COMPLETED = 'completed';
    public const BOOKING_CANCELLED = 'cancelled';
    public const BOOKING_REFUNDED = 'refunded';
    public const BOOKING_NO_SHOW = 'no_show';

    public const CLIENT_ACTIVE = 'active';

    public const CATEGORY_ACTIVE = 'active';
    public const CATEGORY_INACTIVE = 'inactive';

    public const SUPPLIER_ACTIVE = 'active';
    public const SUPPLIER_INACTIVE = 'inactive';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_CANCELLED = 'cancelled';
    public const PAYMENT_STRIPE_SUCCEEDED = 'succeeded';

    public const BLOG_PUBLISHED = 'published';
    public const BLOG_DRAFT = 'draft';

    public const EMPLOYEE_ACTIVE = 'active';
    public const EMPLOYEE_INACTIVE = 'inactive';

    public const POSITION_ACTIVE = 'active';

    protected $table = 'statuses';

    public function __toString()
    {
        return (string) $this->name;
    }

    protected static function ownerColumn(): string
    {
        return 'statusable';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'status_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'status_id');
    }
}
