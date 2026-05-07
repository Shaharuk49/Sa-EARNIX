<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Top 50 users by direct referral count
        $topUsers = User::withCount('downlines as referral_count')
            ->where('is_active', true)
            ->orderByDesc('referral_count')
            ->limit(50)
            ->get();

        /** @var \App\Models\User $currentUser */
        $currentUser     = Auth::user();
        $myRank          = null;
        $myReferralCount = $currentUser->directReferrals()->count();

        foreach ($topUsers as $i => $u) {
            if ($u->id === $currentUser->id) {
                $myRank = $i + 1;
                break;
            }
        }

        return view('user.leaderboard.index', compact('topUsers', 'myRank', 'myReferralCount'));
    }
}
