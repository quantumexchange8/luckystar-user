<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'full_name',
        'kyc_status'
    ];

    public function setReferralId(): void
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = 'LKMx';

        $length = 10 - strlen($randomString);

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        $this->referral_code = $randomString;
        $this->save();
    }

    public function getChildrenIds(): array
    {
        return User::query()->where('hierarchyList', 'like', '%-' . $this->id . '-%')
            ->pluck('id')
            ->toArray();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(GroupRankSetting::class, 'setting_rank_id', 'id');
    }

    public function upline(): BelongsTo
    {
        return $this->belongsTo(User::class, 'upline_id', 'id');
    }

    public function group(): HasOne
    {
        return $this->hasOne(GroupHasUser::class, 'user_id');
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class, 'user_id', 'id');
    }

    public function active_subscriptions(): HasMany
    {
        return $this->hasMany(TradingSubscription::class, 'user_id', 'id')->where('status', 'active');
    }

    public function active_trading_accounts(): HasMany
    {
        return $this->hasMany(TradingAccount::class, 'user_id', 'id')->where('status', 'active');
    }

    public function kycs(): HasMany
    {
        return $this->hasMany(Kyc::class, 'user_id', 'id');
    }

    public function getKycStatusAttribute(): string
    {
        if ($this->kycs()->count() === 0) {
            return 'unverified';
        }

        return $this->kycs->every(fn ($kyc) => $kyc->status === 'verified') ? 'verified' : 'unverified';
    }
}
