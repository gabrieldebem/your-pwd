<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Password extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'password',
        'user_id',
        'url',
    ];

    protected $with = [
        'user',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
