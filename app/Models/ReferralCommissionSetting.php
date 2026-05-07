<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommissionSetting extends Model
{
    protected $fillable = ['generation_number', 'amount', 'is_active'];
    protected $casts    = ['amount' => 'decimal:2', 'is_active' => 'boolean'];
}
