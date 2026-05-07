<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySalaryLevel extends Model
{
    protected $fillable = [
        'level_number', 'title', 'salary_amount', 'is_active_by_admin', 'sort_order',
    ];

    protected $casts = [
        'salary_amount' => 'decimal:2',
        'is_active_by_admin' => 'boolean',
    ];

    public function rules()
    {
        return $this->hasMany(MonthlySalaryRule::class);
    }

    public function claims()
    {
        return $this->hasMany(MonthlySalaryClaim::class);
    }
}
