<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySalaryRule extends Model
{
    protected $fillable = ['monthly_salary_level_id', 'rule_text'];

    public function level()
    {
        return $this->belongsTo(MonthlySalaryLevel::class, 'monthly_salary_level_id');
    }
}
