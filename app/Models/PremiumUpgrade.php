<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumUpgrade extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'gateway_name',
        'gateway_transaction_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
