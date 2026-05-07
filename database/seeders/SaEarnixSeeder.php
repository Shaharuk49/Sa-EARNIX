<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaderBadge;
use App\Models\MonthlySalaryLevel;
use App\Models\WithdrawMethod;

class SaEarnixSeeder extends Seeder
{
    public function run(): void
    {
        // Leader Badges
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LeaderBadge::truncate();
        MonthlySalaryLevel::truncate();
        WithdrawMethod::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $badges = [
            [
                'name' => 'Leader', 'slug' => 'leader', 'sort_order' => 1,
                'condition_text' => "10 verified members using your affiliate ID earn leadership.\n\nIncome:\nTotal: 10,000-15,000 BDT (approx)\nMonthly: 15,000-20,000+ BDT (approx)\n\nTime required: 5-10 days.",
                'prize_text' => "Cashback reward on each achievement\nUnlimited income & recognition\nJoin official leader group\nDigital appreciation post via app",
            ],
            ['name' => 'Silver', 'slug' => 'silver', 'sort_order' => 2,
             'condition_text' => '5 direct referrals must earn Leader badge.', 'prize_text' => 'Silver badge rewards & recognition.'],
            ['name' => 'Gold', 'slug' => 'gold', 'sort_order' => 3,
             'condition_text' => '5 direct referrals must earn Silver badge.', 'prize_text' => 'Gold badge rewards & recognition.'],
            ['name' => 'Diamond', 'slug' => 'diamond', 'sort_order' => 4,
             'condition_text' => '5 direct referrals must earn Gold badge.', 'prize_text' => 'Diamond badge rewards & recognition.'],
            ['name' => 'Max Leader', 'slug' => 'max-leader', 'sort_order' => 5,
             'condition_text' => '5 direct referrals must earn Diamond badge.', 'prize_text' => 'Max Leader rewards & recognition.'],
            ['name' => 'Umrah Haji', 'slug' => 'umrah-haji', 'sort_order' => 6,
             'condition_text' => '5 direct referrals must earn Max Leader badge.', 'prize_text' => 'Umrah Haji tour package reward.'],
        ];
        foreach ($badges as $b) LeaderBadge::create($b);

        // Monthly Salary Levels
        $levels = [
            ['level_number' => 1, 'title' => 'First 3 Months', 'salary_amount' => 14583, 'is_active_by_admin' => false, 'sort_order' => 1],
            ['level_number' => 2, 'title' => 'Next 3 Months',  'salary_amount' => 21875, 'is_active_by_admin' => false, 'sort_order' => 2],
            ['level_number' => 3, 'title' => 'Months 6-9',     'salary_amount' => 29166, 'is_active_by_admin' => false, 'sort_order' => 3],
            ['level_number' => 4, 'title' => 'Lifetime',       'salary_amount' => 36458, 'is_active_by_admin' => false, 'sort_order' => 4],
            ['level_number' => 5, 'title' => 'Lifetime',       'salary_amount' => 54687, 'is_active_by_admin' => false, 'sort_order' => 5],
        ];
        foreach ($levels as $l) MonthlySalaryLevel::create($l);

        // Withdraw Methods
        foreach (['bKash', 'Nagad', 'Rocket', 'Bank Transfer'] as $m) {
            WithdrawMethod::create(['name' => $m, 'is_active' => true]);
        }

        $this->command->info('Seeded: ' . LeaderBadge::count() . ' badges, ' . MonthlySalaryLevel::count() . ' salary levels, ' . WithdrawMethod::count() . ' withdraw methods.');
    }
}
