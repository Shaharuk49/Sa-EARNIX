<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Show team dashboard with direct referrals and total team.
     */
    public function index()
    {
        $user = Auth::user();
        
        $directReferrals = $user->downlines()
            ->where('is_active', true)
            ->orderByDesc('joined_at')
            ->get();

        $totalTeam = $this->getTotalTeamCount($user);
        $directCount = $directReferrals->count();

        return view('user.team.dashboard', compact(
            'user',
            'directReferrals',
            'directCount',
            'totalTeam'
        ));
    }

    /**
     * Show list of business partners (direct referrals with details).
     */
    public function partners()
    {
        $user = Auth::user();
        
        $partners = $user->downlines()
            ->where('is_active', true)
            ->orderByDesc('joined_at')
            ->get()
            ->map(function ($partner) {
                return [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'affiliate_id' => $partner->affiliate_id,
                    'profile_photo' => $partner->profile_photo,
                    'joined_at' => $partner->joined_at,
                    'referrals_count' => $partner->downlines()->where('is_active', true)->count(),
                ];
            });

        return view('user.team.partners', compact('partners'));
    }

    /**
     * Get total team count recursively (all generations).
     */
    private function getTotalTeamCount($user)
    {
        $count = 0;
        foreach ($user->downlines()->where('is_active', true)->get() as $downline) {
            $count += 1 + $this->getTotalTeamCount($downline);
        }
        return $count;
    }
}
