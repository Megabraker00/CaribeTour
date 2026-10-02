<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToStatus;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use BelongsToStatus;
    use HasFactory;
}
