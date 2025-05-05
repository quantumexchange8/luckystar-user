<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTypeHasLeverage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_type_id',
        'setting_leverage_id',
    ];

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id', 'id');
    }

    public function settingLeverage(): BelongsTo
    {
        return $this->belongsTo(SettingLeverage::class, 'setting_leverage_id', 'id');
    }
}
