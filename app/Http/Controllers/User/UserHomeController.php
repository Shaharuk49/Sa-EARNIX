<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    public function index()
    {
        $user        = Auth::user();
        $wallet      = $user->getOrCreateWallet();
        $commissions = Commission::where('receiver_id', $user->id)
            ->with('payer')
            ->latest()
            ->take(10)
            ->get();

        $totalEarned  = Commission::where('receiver_id', $user->id)->sum('amount');
        $directReferrals = $user->downlines()->where('is_active', 1)->count();

        $referralLink = route('register', ['ref' => $user->affiliate_id]);

        return view('user.home', compact(
            'user', 'wallet', 'commissions', 'totalEarned', 'directReferrals', 'referralLink'
        ));
    }

    public function team()
    {
        $user    = Auth::user();
        $downlines = $user->downlines()->with('downlines')->get();
        return view('user.team', compact('user', 'downlines'));
    }

    public function earnings()
    {
        $user = Auth::user();
        $commissions = Commission::where('receiver_id', $user->id)
            ->with('payer')
            ->latest()
            ->paginate(20);
        $totalEarned = Commission::where('receiver_id', $user->id)->sum('amount');
        return view('user.earnings', compact('commissions', 'totalEarned'));
    }
}