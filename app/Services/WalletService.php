<?php

namespace App\Services;

use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function deposit(WalletAccount $walletAccount, $amount, $sourceType, $referenceType = null, $referenceId = null)
    {
        DB::transaction(function () use ($walletAccount, $amount, $sourceType, $referenceType, $referenceId) {
            $walletAccount->current_balance += $amount;
            $walletAccount->save();

            WalletTransaction::create([
                'user_id' => $walletAccount->user_id,
                'type' => 'credit',
                'source_type' => $sourceType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'amount' => $amount,
                'balance_before' => $walletAccount->current_balance - $amount,
                'balance_after' => $walletAccount->current_balance,
                'status' => 'completed',
            ]);
        });
    }

    public function hold(WalletAccount $walletAccount, $amount, $sourceType, $referenceType = null, $referenceId = null)
    {
        DB::transaction(function () use ($walletAccount, $amount, $sourceType, $referenceType, $referenceId) {
            $walletAccount->hold_balance += $amount;
            $walletAccount->save();

            WalletTransaction::create([
                'user_id' => $walletAccount->user_id,
                'type' => 'hold_credit',
                'source_type' => $sourceType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'amount' => $amount,
                'balance_before' => $walletAccount->current_balance,
                'balance_after' => $walletAccount->current_balance,
                'status' => 'pending',
            ]);
        });
    }

    public function releaseHold(WalletAccount $walletAccount, $amount, $sourceType, $referenceType = null, $referenceId = null)
    {
        DB::transaction(function () use ($walletAccount, $amount, $sourceType, $referenceType, $referenceId) {
            $walletAccount->hold_balance -= $amount;
            $walletAccount->current_balance += $amount;
            $walletAccount->save();

            WalletTransaction::create([
                'user_id' => $walletAccount->user_id,
                'type' => 'hold_release',
                'source_type' => $sourceType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'amount' => $amount,
                'balance_before' => $walletAccount->current_balance - $amount,
                'balance_after' => $walletAccount->current_balance,
                'status' => 'completed',
            ]);
        });
    }

    public function cancelHold(WalletAccount $walletAccount, $amount, $sourceType, $referenceType = null, $referenceId = null)
    {
        DB::transaction(function () use ($walletAccount, $amount, $sourceType, $referenceType, $referenceId) {
            $walletAccount->hold_balance -= $amount;
            $walletAccount->save();

            WalletTransaction::create([
                'user_id' => $walletAccount->user_id,
                'type' => 'hold_cancel',
                'source_type' => $sourceType,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'amount' => $amount,
                'balance_before' => $walletAccount->current_balance,
                'balance_after' => $walletAccount->current_balance,
                'status' => 'cancelled',
            ]);
        });
    }
}