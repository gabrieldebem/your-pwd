<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_agent',
        'ips',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('account', function (Builder $builder) {
            if ((auth()->user() ?? new User())::class === User::class) {
                $builder->where('user_id', auth()->id());
            }
        });
    }
}
