<?php

namespace App\Services;

use App\Models\MonthlySalaryClaim;
use App\Models\MonthlySalaryLevel;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class MonthlySalaryService
{
    public function processSalary(User $user)
    {
        $level = $user->monthlySalaryClaims()->first()->monthly_salary_level_id;  // Get user's salary level

        $salaryLevel = MonthlySalaryLevel::find($level);
        $salaryAmount = $salaryLevel->salary_amount;

        // Ensure it's within the claimable period
        $this->validateClaimPeriod($user);

        // Claim salary
        $this->claimSalary($user, $salaryAmount);
    }

    protected function validateClaimPeriod(User $user)
    {
        $currentDate = now();
        $firstClaimDate = $user->monthlySalaryClaims()->first()->created_at;

        if ($currentDate->diffInDays($firstClaimDate) > 5) {
            throw new \Exception("Salary can only be claimed between 1st-5th of each month.");
        }
    }

    protected function claimSalary(User $user, $amount)
    {
        MonthlySalaryClaim::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);
    }
}