<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RegistrationPayment;
use App\Models\WithdrawRequest;
use App\Models\WalletTransaction;
use App\Models\ReferralCommission;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'            => User::count(),
            'active_users'           => User::where('is_active', 1)->count(),
            'premium_users'          => User::where('is_premium', 1)->count(),
            'pending_withdraws'      => WithdrawRequest::where('status', 'pending')->count(),
            'pending_registrations'  => RegistrationPayment::where('status', 'pending')->count(),
            'total_paid_out'         => WalletTransaction::where('type', 'credit')->where('status', 'completed')->sum('amount'),
            'today_registrations'    => User::whereDate('created_at', today())->count(),
        ];

        $recentUsers      = User::latest()->take(8)->get();
        $pendingWithdraws = WithdrawRequest::with(['user', 'method'])->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'pendingWithdraws'));
    }

    public function users()
    {
        $users = User::withCount('directReferrals as referral_count')->latest()->paginate(30);
        return view('admin.users.index', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load(['upline', 'walletAccount']);

        $stats = [
            'direct_referrals' => $user->directReferrals()->count(),
            'earned_badges' => $user->leaderBadges()->count(),
            'total_commissions' => ReferralCommission::where('earner_user_id', $user->id)->sum('commission_amount'),
            'total_withdraw_requested' => WithdrawRequest::where('user_id', $user->id)->sum('amount'),
        ];

        $recentTransactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $withdrawRequests = WithdrawRequest::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $registrationPayment = RegistrationPayment::where('user_id', $user->id)->latest()->first();

        return view('admin.users.show', compact(
            'user',
            'stats',
            'recentTransactions',
            'withdrawRequests',
            'registrationPayment'
        ));
    }
}
