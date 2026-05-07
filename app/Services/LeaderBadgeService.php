<?php

namespace App\Services;

use App\Models\User;
use App\Models\LeaderBadge;
use Illuminate\Support\Facades\DB;

class LeaderBadgeService
{
    public function checkAndAssignBadge(User $user)
    {
        $directReferrals = $user->directReferrals()->count();

        if ($directReferrals >= 10) {
            $badge = LeaderBadge::where('slug', 'leader')->first();
            $this->assignBadge($user, $badge);
        }
    }

    protected function assignBadge(User $user, LeaderBadge $badge)
    {
        $user->leaderBadges()->attach($badge->id, ['awarded_at' => now()]);
    }
}