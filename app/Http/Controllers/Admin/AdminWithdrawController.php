<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminWithdrawController extends Controller
{
    public function index(Request $request)
    {
        $status   = $request->query('status', 'pending');
        $withdraws = WithdrawRequest::with(['user', 'method'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest('requested_at')
            ->paginate(20);

        return view('admin.withdraw.index', compact('withdraws', 'status'));
    }

    public function approve(WithdrawRequest $withdraw)
    {
        if ($withdraw->status !== 'pending') {
            return back()->with('error', 'This request is already processed.');
        }

        DB::transaction(function () use ($withdraw) {
            $withdraw->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'reviewed_by' => Auth::guard('admin')->id(),
            ]);

            // Move from hold to withdrawn (deduct hold balance)
            $wallet = WalletAccount::where('user_id', $withdraw->user_id)->first();
            if ($wallet) {
                $wallet->decrement('hold_balance', $withdraw->amount);
                $wallet->increment('total_withdrawn', $withdraw->amount);
            }

            // Update wallet transaction status
            WalletTransaction::where('reference_type', 'withdraw_request')
                ->where('reference_id', $withdraw->id)
                ->update(['status' => 'completed']);
        });

        return back()->with('success', 'Withdraw approved successfully.');
    }

    public function reject(Request $request, WithdrawRequest $withdraw)
    {
        if ($withdraw->status !== 'pending') {
            return back()->with('error', 'This request is already processed.');
        }

        $request->validate(['remarks' => 'nullable|string|max:500']);

        DB::transaction(function () use ($withdraw, $request) {
            $withdraw->update([
                'status'      => 'rejected',
                'rejected_at' => now(),
                'reviewed_by' => Auth::guard('admin')->id(),
                'remarks'     => $request->remarks,
            ]);

            // Refund: move hold back to current balance
            $wallet = WalletAccount::where('user_id', $withdraw->user_id)->first();
            if ($wallet) {
                $wallet->decrement('hold_balance', $withdraw->amount);
                $wallet->increment('current_balance', $withdraw->amount);
            }

            // Credit back transaction
            WalletTransaction::create([
                'user_id'        => $withdraw->user_id,
                'type'           => 'credit',
                'source_type'    => 'withdraw_refund',
                'reference_type' => 'withdraw_request',
                'reference_id'   => $withdraw->id,
                'amount'         => $withdraw->amount,
                'balance_before' => $wallet->current_balance ?? 0,
                'balance_after'  => ($wallet->current_balance ?? 0) + $withdraw->amount,
                'status'         => 'completed',
                'note'           => 'Withdraw rejected – refunded',
            ]);

            WalletTransaction::where('reference_type', 'withdraw_request')
                ->where('reference_id', $withdraw->id)
                ->where('type', 'debit')
                ->update(['status' => 'rejected']);
        });

        return back()->with('success', 'Withdraw rejected and amount refunded.');
    }
}
