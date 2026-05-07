<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model
{
    protected $fillable = [
        'name', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function requests()
    {
        return $this->hasMany(WithdrawRequest::class);
    }
}
