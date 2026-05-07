<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ReferralCommissionSettingSeeder::class,
            WithdrawMethodSeeder::class,
            LeaderBadgeSeeder::class,
            MonthlySalaryLevelSeeder::class,
            BonusSectionSeeder::class,
        ]);
    }
}