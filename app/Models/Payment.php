<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Concerns\BelongsToStatus;

class Payment extends Model
{
    use BelongsToStatus;
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'currency',
        'transaction_id',
        'status_id',
        'type_id',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

}
