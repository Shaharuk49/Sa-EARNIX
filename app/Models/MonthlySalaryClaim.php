<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySalaryClaim extends Model
{
    protected $fillable = [
        'user_id', 'monthly_salary_level_id', 'claim_month', 'amount', 'status', 'claimed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(MonthlySalaryLevel::class, 'monthly_salary_level_id');
    }
}
