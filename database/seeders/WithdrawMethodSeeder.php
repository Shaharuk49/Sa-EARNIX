<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WithdrawMethod;

class WithdrawMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = ['bKash', 'Nagad', 'Rocket'];

        foreach ($methods as $methodName) {
            WithdrawMethod::updateOrCreate(
                ['name' => $methodName],
                ['is_active' => true]
            );
        }
    }
}