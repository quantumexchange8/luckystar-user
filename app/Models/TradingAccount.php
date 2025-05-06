<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradingAccount extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    // Relations
    public function trading_user(): BelongsTo
    {
        return $this->belongsTo(TradingUser::class, 'meta_login', 'meta_login');
    }
}
