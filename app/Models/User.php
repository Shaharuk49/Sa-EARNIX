<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'affiliate_id',
        'upline_user_id',
        'name',
        'email',
        'phone',
        'username',
        'password',
        'profile_photo',
        'is_active',
        'is_premium',
        'transaction_password',
        'preferred_language',
        'joined_at',
        'last_login_at',
        'activated_at',
        'banned_at',
    ];

    protected $hidden = ['password', 'remember_token', 'transaction_password'];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_premium'   => 'boolean',
        'joined_at'    => 'datetime',
        'last_login_at'=> 'datetime',
        'activated_at' => 'datetime',
        'banned_at'    => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->affiliate_id)) {
                $user->affiliate_id = strtoupper(Str::random(8));
            }
        });
    }

    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_user_id');
    }

    public function downlines()
    {
        return $this->hasMany(User::class, 'upline_user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'receiver_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getOrCreateWallet()
    {
        return $this->wallet ?? $this->wallet()->create(['balance' => 0]);
    }

    public function walletAccount()
    {
        return $this->hasOne(WalletAccount::class);
    }

    public function directReferrals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'upline_user_id');
    }

    public function referralCommissions()
    {
        return $this->hasMany(ReferralCommission::class, 'earner_user_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function leaderBadges()
    {
        return $this->hasMany(UserLeaderBadge::class);
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }
}