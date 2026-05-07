<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\GenerationCommission;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    public const REGISTRATION_FEE   = 250;
    public const TOTAL_COMMISSION   = 220;
    public const COMPANY_PROFIT     = 30;
    public const MAX_GENERATIONS    = 24;

    /**
     * Distribute referral commissions up 24 generations for a new user.
     */
    public function distribute(User $newUser): void
    {
        $rates   = GenerationCommission::getRates();
        $current = $newUser->upline;
        $gen     = 1;

        DB::transaction(function () use ($rates, $current, $gen, $newUser) {
            while ($current && $gen <= self::MAX_GENERATIONS) {
                $amount = $rates[$gen] ?? 0;

                if ($amount > 0) {
                    // Credit wallet
                    $wallet = $current->getOrCreateWallet();
                    $wallet->credit($amount);

                    // Log commission
                    Commission::create([
                        'receiver_id' => $current->id,
                        'payer_id'    => $newUser->id,
                        'generation'  => $gen,
                        'amount'      => $amount,
                        'type'        => 'referral',
                        'description' => "Generation {$gen} commission from {$newUser->name}",
                    ]);
                }

                $current = $current->upline;
                $gen++;
            }

            // Remaining generations without upline → company (logged only)
            while ($gen <= self::MAX_GENERATIONS) {
                $amount = $rates[$gen] ?? 0;
                if ($amount > 0) {
                    Commission::create([
                        'receiver_id' => null,
                        'payer_id'    => $newUser->id,
                        'generation'  => $gen,
                        'amount'      => $amount,
                        'type'        => 'company',
                        'description' => "Generation {$gen} – no upline, to company",
                    ]);
                }
                $gen++;
            }
        });
    }
}