<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MonthlySalaryLevel;

class MonthlySalaryLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['level_number' => 1, 'title' => 'Level 1', 'salary_amount' => 11666, 'sort_order' => 1],
            ['level_number' => 2, 'title' => 'Level 2', 'salary_amount' => 17500, 'sort_order' => 2],
            ['level_number' => 3, 'title' => 'Level 3', 'salary_amount' => 29166, 'sort_order' => 3],
            ['level_number' => 4, 'title' => 'Level 4', 'salary_amount' => 36458, 'sort_order' => 4],
            ['level_number' => 5, 'title' => 'Level 5', 'salary_amount' => 54687, 'sort_order' => 5],
        ];

        foreach ($levels as $level) {
            MonthlySalaryLevel::updateOrCreate(
                ['level_number' => $level['level_number']],
                $level
            );
        }
    }
}