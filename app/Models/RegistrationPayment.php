<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationPayment extends Model
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
        'raw_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
