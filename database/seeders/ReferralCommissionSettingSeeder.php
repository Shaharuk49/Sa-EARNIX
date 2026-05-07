<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReferralCommissionSetting;

class ReferralCommissionSettingSeeder extends Seeder
{
    public function run(): void
    {
        for ($generation = 1; $generation <= 24; $generation++) {
            ReferralCommissionSetting::updateOrCreate(
                ['generation_number' => $generation],
                [
                    'amount' => 0,
                    'is_active' => true,
                ]
            );
        }
    }
}