<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MonthlySalaryLevel;
use App\Models\MonthlySalaryClaim;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlySalaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $levels = MonthlySalaryLevel::with('rules')->orderBy('level_number')->get();
        $currentMonth = Carbon::now()->format('Y-m');
        $today = Carbon::now()->day;

        // Claim window: 1st to 5th of each month
        $canClaimToday = ($today >= 1 && $today <= 5);

        $levelData = $levels->map(function ($level) use ($user, $currentMonth, $canClaimToday) {
            $claim = MonthlySalaryClaim::where('user_id', $user->id)
                ->where('monthly_salary_level_id', $level->id)
                ->where('claim_month', $currentMonth)
                ->first();

            return [
                'level'        => $level,
                'claimed'      => $claim !== null,
                'claim_amount' => $claim ? $claim->amount : $level->salary_amount,
                'can_claim'    => $level->is_active_by_admin && $canClaimToday && !$claim,
            ];
        });

        $claimHistory = MonthlySalaryClaim::with('level')
            ->where('user_id', $user->id)
            ->orderByDesc('claimed_at')
            ->get();

        $globalRules = \App\Models\AdminSetting::where('key_name', 'monthly_salary_rules')->value('value_text') ?? '';

        return view('user.salary.index', compact('levelData', 'claimHistory', 'canClaimToday', 'globalRules'));
    }

    public function claim(Request $request)
    {
        $request->validate(['level_id' => 'required|exists:monthly_salary_levels,id']);

        $user  = Auth::user();
        $today = Carbon::now()->day;

        if ($today < 1 || $today > 5) {
            return back()->with('error', 'Salary can only be claimed between 1st-5th of each month.');
        }

        $level = MonthlySalaryLevel::findOrFail($request->level_id);

        if (!$level->is_active_by_admin) {
            return back()->with('error', 'This salary level is not yet activated by admin.');
        }

        $currentMonth = Carbon::now()->format('Y-m');

        $alreadyClaimed = MonthlySalaryClaim::where('user_id', $user->id)
            ->where('monthly_salary_level_id', $level->id)
            ->where('claim_month', $currentMonth)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'You have already claimed this level for this month.');
        }

        DB::transaction(function () use ($user, $level, $currentMonth) {
            MonthlySalaryClaim::create([
                'user_id'                => $user->id,
                'monthly_salary_level_id' => $level->id,
                'claim_month'            => $currentMonth,
                'amount'                 => $level->salary_amount,
                'status'                 => 'claimed',
                'claimed_at'             => now(),
            ]);

            // Credit wallet
            $wallet = WalletAccount::firstOrCreate(
                ['user_id' => $user->id],
                ['current_balance' => 0, 'hold_balance' => 0, 'total_earned' => 0]
            );

            WalletTransaction::create([
                'user_id'          => $user->id,
                'type'             => 'credit',
                'source_type'      => 'monthly_salary',
                'reference_type'   => 'monthly_salary_claim',
                'reference_id'     => $level->id,
                'amount'           => $level->salary_amount,
                'balance_before'   => $wallet->current_balance,
                'balance_after'    => $wallet->current_balance + intval($level->salary_amount),
                'status'           => 'completed',
                'note'             => 'Monthly Salary Level ' . $level->level_number,
            ]);

            $wallet->increment('current_balance', intval($level->salary_amount));
            $wallet->increment('total_earned', intval($level->salary_amount));
        });

        return back()->with('success', 'Monthly salary claimed successfully!');
    }
}
