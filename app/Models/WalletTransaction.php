<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'source_type',
        'reference_type',
        'reference_id',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'note',
    ];
}
