<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * Show income dashboard with daily, weekly, monthly earnings.
     */
    public function index()
    {
        $user = Auth::user();
        $walletAccount = WalletAccount::firstOrCreate(
            ['user_id' => $user->id],
            ['current_balance' => 0, 'hold_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        $now = now();
        $todayStart = $now->clone()->startOfDay();
        $yesterdayStart = $now->clone()->subDay()->startOfDay();
        $yesterdayEnd = $now->clone()->subDay()->endOfDay();
        $sevenDaysAgo = $now->clone()->subDays(7)->startOfDay();
        $thirtyDaysAgo = $now->clone()->subDays(30)->startOfDay();

        $todayIncome = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$todayStart, now()])
            ->sum('amount');

        $yesterdayIncome = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('amount');

        $sevenDayIncome = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$sevenDaysAgo, now()])
            ->sum('amount');

        $thirtyDayIncome = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$thirtyDaysAgo, now()])
            ->sum('amount');

        $totalIncome = (float) $walletAccount->total_earned;

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('user.income.dashboard', compact(
            'walletAccount',
            'todayIncome',
            'yesterdayIncome',
            'sevenDayIncome',
            'thirtyDayIncome',
            'totalIncome',
            'transactions'
        ));
    }

    /**
     * Show income history with pagination.
     */
    public function history()
    {
        $user = Auth::user();
        
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('user.income.history', compact('transactions'));
    }
}
