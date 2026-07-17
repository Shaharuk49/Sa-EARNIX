<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\PremiumUpgrade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPremiumController extends Controller
{
    /**
     * List premium upgrade payment claims, newest pending first.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $upgrades = PremiumUpgrade::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending' => PremiumUpgrade::where('status', 'pending')->count(),
            'paid' => PremiumUpgrade::where('status', 'paid')->count(),
            'rejected' => PremiumUpgrade::where('status', 'rejected')->count(),
        ];

        return view('admin.premium.index', compact('upgrades', 'status', 'counts'));
    }

    /**
     * Approve a pending upgrade: activate premium for the user and
     * credit the company wallet with the payment amount.
     */
    public function approve(PremiumUpgrade $premium)
    {
        if ($premium->status !== 'pending') {
            return back()->with('error', 'This claim has already been reviewed.');
        }

        DB::transaction(function () use ($premium) {
            $premium->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            User::whereKey($premium->user_id)->update(['is_premium' => true]);

            $existing = AdminSetting::where('key_name', 'company_wallet_balance')->first();
            $balance = $existing ? (float) $existing->value_text : 0;

            AdminSetting::updateOrCreate(
                ['key_name' => 'company_wallet_balance'],
                ['value_text' => (string) ($balance + $premium->amount)]
            );
        });

        return back()->with('success', "Premium approved for {$premium->user->name}.");
    }

    /**
     * Reject a pending upgrade claim (e.g. invalid or unmatched transaction ID).
     */
    public function reject(Request $request, PremiumUpgrade $premium)
    {
        if ($premium->status !== 'pending') {
            return back()->with('error', 'This claim has already been reviewed.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $premium->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "Premium claim for {$premium->user->name} rejected.");
    }
     
}