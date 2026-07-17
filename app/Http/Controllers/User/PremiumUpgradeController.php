<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\PremiumUpgrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * A submission the user made that's still awaiting admin review, if any.
     */
    private function pendingUpgradeFor($user): ?PremiumUpgrade
    {
        return PremiumUpgrade::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
    }

    /**
     * Show the "what you get" premium benefits page.
     */
    public function benefits()
    {
        $user = Auth::user();

        if ($user->is_premium) {
            return redirect()->route('user.home')->with('info', 'আপনি ইতিমধ্যেই premium member.');
        }

        if ($pending = $this->pendingUpgradeFor($user)) {
            return redirect()->route('premium.upgrade.status', $pending);
        }

        return view('user.premium.benefits', [
            'amount' => $this->getUpgradeAmount(),
        ]);
    }

    /**
     * Show premium upgrade payment form.
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->is_premium) {
            return redirect()->route('user.home')->with('info', 'আপনি ইতিমধ্যেই premium member.');
        }

        if ($pending = $this->pendingUpgradeFor($user)) {
            return redirect()->route('premium.upgrade.status', $pending)
                ->with('info', 'আপনার আগের payment টি এখনো review হচ্ছে।');
        }

        return view('user.premium.upgrade', [
            'amount' => $this->getUpgradeAmount(),
            'paymentPhone' => $this->getPaymentPhone(),
        ]);
    }

    /**
     * Process premium upgrade payment submission.
     * Does NOT activate premium — it just records the claim as pending
     * until an admin verifies and approves it.
     */
    public function process(Request $request)
    {
        $user = Auth::user();

        if ($user->is_premium) {
            return redirect()->route('user.home')->with('info', 'আপনি ইতিমধ্যে premium member.');
        }

        if ($pending = $this->pendingUpgradeFor($user)) {
            return redirect()->route('premium.upgrade.status', $pending)
                ->with('info', 'আপনার আগের payment টি এখনো review হচ্ছে।');
        }

        $request->validate([
            'payment_method' => 'required|string|in:bkash,nagad,rocket,card',
            'transaction_ref' => 'required|string|max:100',
        ]);

        $amount = $this->getUpgradeAmount();

        $premium = PremiumUpgrade::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'currency' => 'BDT',
            'payment_method' => $request->payment_method,
            'gateway_name' => 'manual',
            'gateway_transaction_id' => $request->transaction_ref,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return redirect()->route('premium.upgrade.status', $premium)
            ->with('success', 'আপনার payment জমা হয়েছে, admin verify করার পর premium active হবে।');
    }

    /**
     * Show the status of a submitted upgrade (pending / approved / rejected).
     */
    public function status(PremiumUpgrade $premium)
    {
        abort_unless($premium->user_id === Auth::id(), 403);

        return view('user.premium.status', [
            'premium' => $premium,
        ]);
    }
}