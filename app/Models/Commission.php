<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'receiver_id',
        'payer_id',
        'generation',
        'amount',
        'type',   // referral | company
        'description',
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }
}