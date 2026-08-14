<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use App\Models\WithdrawMethod;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WithdrawController extends Controller
{
    private const MIN_WITHDRAW = 10;

    public function index()
    {
        $user    = Auth::user();
        $wallet  = WalletAccount::firstOrCreate(
            ['user_id' => $user->id],
            ['current_balance' => 0, 'hold_balance' => 0, 'total_earned' => 0]
        );
        $methods  = WithdrawMethod::where('is_active', true)->get();
        $history  = WithdrawRequest::with('method')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.withdraw.index', compact('wallet', 'methods', 'history'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'amount'              => 'required|numeric|min:' . self::MIN_WITHDRAW,
            'withdraw_method_id'  => 'required|exists:withdraw_methods,id',
            'account_number'      => 'required|string|max:30',
        ]);

        $user   = Auth::user();

        // Transaction password requirement removed (no verification performed)

        $wallet = WalletAccount::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->current_balance < $request->amount) {
            return back()->with('error', 'Insufficient balance.')->withInput();
        }

        DB::transaction(function () use ($user, $request, $wallet) {
            // Deduct from balance and move to hold
            $wallet->decrement('current_balance', $request->amount);
            $wallet->increment('hold_balance', $request->amount);

            WithdrawRequest::create([
                'user_id'            => $user->id,
                'withdraw_method_id' => $request->withdraw_method_id,
                'amount'             => $request->amount,
                'account_number'     => $request->account_number,
                'status'             => 'pending',
                'requested_at'       => now(),
            ]);

            WalletTransaction::create([
                'user_id'          => $user->id,
                'wallet_account_id'=> $wallet->id,
                'type'             => 'debit',
                'amount'           => $request->amount,
                'source_type'      => 'withdraw',
                'description'      => 'Withdraw Request - pending approval',
                'status'           => 'pending',
                'transacted_at'    => now(),
            ]);
        });

        return back()->with('success', 'Withdraw request submitted! It will be processed within 24 hours.');
    }
}
