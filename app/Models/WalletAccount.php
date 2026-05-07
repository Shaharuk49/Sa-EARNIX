<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_balance',
        'hold_balance',
        'total_earned',
        'total_withdrawn',
    ];

    // Relationship: WalletAccount belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: WalletAccount has many WalletTransactions
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}