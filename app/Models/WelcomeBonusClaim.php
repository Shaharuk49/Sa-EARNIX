<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeBonusClaim extends Model
{
    use HasFactory;

    const BONUS_AMOUNT = 1000; // 1000 BDT

    protected $fillable = [
        'user_id',
        'amount',
        'claimed_at',
        'status',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    // Relationship: WelcomeBonusClaim belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
