<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\BelongsToStatus;

class Employee extends Model
{
    use BelongsToStatus;
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'email_personal',
        'email_company',
        'dni_passport',
        'phone',
        'position_id',
        'status_id',
        'created_user_id',
    ];

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->createdUser();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

}
