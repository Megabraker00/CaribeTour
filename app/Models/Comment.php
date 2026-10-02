<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use BelongsToStatus;
    use HasFactory;
}
