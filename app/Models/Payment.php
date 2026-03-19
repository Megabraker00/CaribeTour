<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
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

    public function status(): MorphOne
    {
        return $this->morphOne(Status::class, 'statusable');
    }

    public function statusRecord(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
