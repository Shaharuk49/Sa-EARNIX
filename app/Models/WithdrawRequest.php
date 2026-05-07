<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $fillable = [
        'user_id', 'withdraw_method_id', 'amount', 'account_number',
        'status', 'requested_at', 'approved_at', 'rejected_at', 'reviewed_by', 'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function method()
    {
        return $this->belongsTo(WithdrawMethod::class, 'withdraw_method_id');
    }
}
