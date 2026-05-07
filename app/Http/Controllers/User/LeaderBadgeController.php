<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LeaderBadge;
use App\Models\UserLeaderBadge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderBadgeController extends Controller
{
    /**
     * Badge definitions: slug => required direct referrals who hold previous badge
     * For 'leader': just direct referrals count
     */
    private const BADGE_REQUIREMENTS = [
        'leader'               => ['type' => 'direct_count',      'required' => 10],
        'bronze-leader'        => ['type' => 'direct_with_badge', 'badge' => 'leader',         'required' => 10],
        'silver-leader'        => ['type' => 'direct_with_badge', 'badge' => 'bronze-leader',  'required' => 8],
        'platinum-leader'      => ['type' => 'direct_with_badge', 'badge' => 'silver-leader',  'required' => 7],
        'gold-leader'          => ['type' => 'direct_with_badge', 'badge' => 'platinum-leader','required' => 6],
        'diamond-leader'       => ['type' => 'direct_with_badge', 'badge' => 'gold-leader',    'required' => 5],
        'crown-leader'         => ['type' => 'direct_with_badge', 'badge' => 'diamond-leader', 'required' => 5],
        'sa-crown-elite-leader'=> ['type' => 'direct_with_badge', 'badge' => 'crown-leader',   'required' => 3],
    ];

    public function index()
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $badges  = LeaderBadge::orderBy('sort_order')->get();

        // Check qualification for each badge
        $badgeData = $badges->map(function ($badge) use ($user) {
            [$qualified, $current, $required] = $this->checkQualification($user, $badge->slug);
            $earned = UserLeaderBadge::where('user_id', $user->id)
                ->where('leader_badge_id', $badge->id)->exists();
            $qualified = (bool) $qualified;

            return compact('badge', 'qualified', 'current', 'required', 'earned');
        });

        // Current badge = highest earned
        $currentBadge = null;
        foreach ($badgeData->reverse() as $item) {
            if ($item['earned']) { $currentBadge = $item['badge']; break; }
        }

        return view('user.badge.index', compact('badgeData', 'currentBadge'));
    }

    public function show(LeaderBadge $leaderBadge)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        [$qualified, $current, $required] = $this->checkQualification($user, $leaderBadge->slug);
        $earned = UserLeaderBadge::where('user_id', $user->id)
            ->where('leader_badge_id', $leaderBadge->id)->exists();

        // Get list of direct referrals who earned the prerequisite badge
        $prereqBadge = null;
        if (isset(self::BADGE_REQUIREMENTS[$leaderBadge->slug])) {
            $req = self::BADGE_REQUIREMENTS[$leaderBadge->slug];
            if ($req['type'] === 'direct_with_badge') {
                $prereqBadge = LeaderBadge::where('slug', $req['badge'])->first();
            }
        }

        $qualifiedDirects = collect();
        if ($prereqBadge) {
            $directIds = $user->directReferrals()->pluck('id');
            $qualifiedDirects = User::whereIn('id', $directIds)
                ->whereHas('leaderBadges', fn($q) => $q->where('leader_badge_id', $prereqBadge->id))
                ->with('userProfile')
                ->get();
        } else {
            $qualifiedDirects = $user->directReferrals()->with('userProfile')->get();
        }

        return view('user.badge.show', compact('leaderBadge', 'qualified', 'current', 'required', 'earned', 'qualifiedDirects'));
    }

    private function checkQualification(User $user, string $slug): array
    {
        if (!isset(self::BADGE_REQUIREMENTS[$slug])) {
            return [false, 0, 0];
        }

        $req = self::BADGE_REQUIREMENTS[$slug];

        if ($req['type'] === 'direct_count') {
            $current  = $user->directReferrals()->count();
            $required = $req['required'];
        } else {
            // direct referrals who hold the prerequisite badge
            $prereqBadge = LeaderBadge::where('slug', $req['badge'])->first();
            if (!$prereqBadge) return [false, 0, $req['required']];

            $directIds = $user->directReferrals()->pluck('id');
            $current   = UserLeaderBadge::where('leader_badge_id', $prereqBadge->id)
                ->whereIn('user_id', $directIds)->count();
            $required  = $req['required'];
        }

        return [$current >= $required, $current, $required];
    }
}
