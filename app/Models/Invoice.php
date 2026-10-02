<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'booking_id',
        'rectifies_invoice_id',
        'created_user_id',
        'total_amount',
        'currency',
        'billing_name',
        'billing_vat',
        'billing_address',
        'billing_city',
        'billing_country',
        'issue_date',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'issue_date' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function rectifiedInvoice(): BelongsTo
    {
        return $this->belongsTo(self::class, 'rectifies_invoice_id');
    }

    public function creditNotes(): HasMany
    {
        return $this->hasMany(self::class, 'rectifies_invoice_id');
    }

    public function isCreditNote(): bool
    {
        return $this->rectifies_invoice_id !== null || (float) $this->total_amount < 0;
    }

    public function isPositive(): bool
    {
        return $this->rectifies_invoice_id === null && (float) $this->total_amount > 0;
    }

    public function hasBeenCredited(): bool
    {
        if ($this->relationLoaded('creditNotes')) {
            return $this->creditNotes->isNotEmpty();
        }

        return $this->creditNotes()->exists();
    }
}
