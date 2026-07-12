<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\PremiumUpgrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PremiumUpgradeController extends Controller
{
    private const DEFAULT_UPGRADE_FEE = 250;

    private function getUpgradeAmount(): float
    {
        $amount = AdminSetting::where('key_name', 'premium_upgrade_amount')->value('value_text');

        return (float) ($amount ?: self::DEFAULT_UPGRADE_FEE);
    }

    private function getPaymentPhone(): string
    {
        return AdminSetting::where('key_name', 'premium_upgrade_payment_phone')->value('value_text') ?? '';
    }

    /**
     * Show premium upgrade form.
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->is_premium) {
            return redirect()->route('user.home')->with('info', 'আপনি ইতিমধ্যেই premium member.');
        }

        return view('user.premium.upgrade', [
            'amount' => $this->getUpgradeAmount(),
            'paymentPhone' => $this->getPaymentPhone(),
        ]);
    }

    /**
     * Process premium upgrade payment.
     */
    public function process(Request $request)
    {
        $user = Auth::user();

        if ($user->is_premium) {
            return redirect()->route('user.home')->with('info', 'আপনি ইতিমধ্যে premium member.');
        }

        $request->validate([
            'payment_method' => 'required|string|in:bkash,nagad,rocket,card',
            'transaction_ref' => 'required|string|max:100',
        ]);

        $amount = $this->getUpgradeAmount();

        DB::transaction(function () use ($user, $request, $amount) {
            $premium = PremiumUpgrade::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'currency' => 'BDT',
                'payment_method' => $request->payment_method,
                'gateway_name' => 'manual',
                'gateway_transaction_id' => $request->transaction_ref,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            \App\Models\User::whereKey($user->id)->update(['is_premium' => true]);

            $existing = AdminSetting::where('key_name', 'company_wallet_balance')->first();
            $balance = $existing ? (float) $existing->value_text : 0;

            AdminSetting::updateOrCreate(
                ['key_name' => 'company_wallet_balance'],
                ['value_text' => (string) ($balance + $amount)]
            );
        });

        return redirect()->route('user.home')
            ->with('success', 'Premium upgrade successful! আপনার account premium হয়েছে।');
    }
}
