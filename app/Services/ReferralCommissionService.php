<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\ReferralCommissionSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReferralCommissionService
{
    public function processRegistrationCommission(User $user)
    {
        // Retrieve the referral commission settings for each generation
        $commissionSettings = ReferralCommissionSetting::where('is_active', true)->get();

        // Start the recursive commission distribution process
        $this->distributeCommission($user, $commissionSettings);
    }

    protected function distributeCommission(User $user, $commissionSettings, $generation = 1)
    {
        // Avoid exceeding 24 generations
        if ($generation > 24) {
            return;
        }

        // Check if commission setting for this generation exists
        $commissionSetting = $commissionSettings->firstWhere('generation_number', $generation);
        if (!$commissionSetting) {
            return;
        }

        // Calculate the commission for the user at this generation
        $commissionAmount = $commissionSetting->amount;

        // Create commission entry for the user at this generation
        ReferralCommission::create([
            'earner_user_id' => $user->id,
            'from_user_id' => $user->upline_user_id,
            'generation_number' => $generation,
            'source_type' => 'registration',
            'source_reference_id' => null,  // Can be linked to a specific registration if needed
            'base_amount' => $commissionAmount,
            'commission_amount' => $commissionAmount,
            'status' => 'completed',
        ]);

        // Recursively distribute to the next generation (upline)
        $uplineUser = $user->affiliate;
        if ($uplineUser) {
            $this->distributeCommission($uplineUser, $commissionSettings, $generation + 1);
        }
    }
}