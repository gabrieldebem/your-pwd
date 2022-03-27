<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasUuid;

    protected $casts = [
        'id' => 'string',
    ];
}
