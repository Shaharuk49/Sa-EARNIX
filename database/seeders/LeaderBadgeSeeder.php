<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaderBadge;

class LeaderBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['name' => 'Leader', 'slug' => 'leader', 'sort_order' => 1],
            ['name' => 'Bronze Leader', 'slug' => 'bronze_leader', 'sort_order' => 2],
            ['name' => 'Silver Leader', 'slug' => 'silver_leader', 'sort_order' => 3],
            ['name' => 'Platinum Leader', 'slug' => 'platinum_leader', 'sort_order' => 4],
            ['name' => 'Gold Leader', 'slug' => 'gold_leader', 'sort_order' => 5],
            ['name' => 'Diamond Leader', 'slug' => 'diamond_leader', 'sort_order' => 6],
            ['name' => 'Crown Leader', 'slug' => 'crown_leader', 'sort_order' => 7],
            ['name' => 'SA Crown Elite Leader', 'slug' => 'sa_crown_elite_leader', 'sort_order' => 8],
        ];

        foreach ($badges as $badge) {
            LeaderBadge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}