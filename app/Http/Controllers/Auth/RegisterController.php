<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\ReferralCommission;
use App\Models\ReferralCommissionSetting;
use App\Models\RegistrationPayment;
use App\Models\User;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    private const REGISTRATION_FEE = 250;

    /**
     * Step 1: Show registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $referralCode = $request->query('ref');
        $upline = null;
        if ($referralCode) {
            $upline = User::where('affiliate_id', $referralCode)->first();
        }
        return view('auth.register', compact('referralCode', 'upline'));
    }

    /**
     * Step 2: Store registration data in session, redirect to payment.
     */
    public function storeRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username|alpha_dash',
            'password'     => 'required|string|min:6|confirmed',
            'referral_code'=> 'nullable|exists:users,affiliate_id',
        ], [
            'username.unique'    => 'এই username ইতিমধ্যে ব্যবহার হয়েছে।',
            'referral_code.exists' => 'Referral code সঠিক নয়।',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Save pending registration in session
        Session::put('pending_registration', [
            'name'          => $request->name,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'referral_code' => $request->referral_code,
        ]);

        return redirect()->route('payment.show');
    }

    /**
     * Step 3: Show payment form for registration fee.
     */
    public function showPaymentPage()
    {
        if (!Session::has('pending_registration')) {
            return redirect()->route('register')->with('error', 'প্রথমে registration form পূরণ করুন।');
        }

        return view('auth.registration-payment', [
            'amount' => self::REGISTRATION_FEE,
        ]);
    }

    /**
     * Step 4: Store payment info and wait for admin approval.
     */
    public function processPayment(Request $request)
    {
        if (!Session::has('pending_registration')) {
            return redirect()->route('register')->with('error', 'Registration session পাওয়া যায়নি। আবার চেষ্টা করুন।');
        }

        $request->validate([
            'payment_method'  => 'required|string|in:bkash,nagad,rocket,card',
            'transaction_ref' => 'required|string|max:100|unique:registration_payments,gateway_transaction_id',
        ], [
            'transaction_ref.unique' => 'এই Transaction ID ইতিমধ্যে ব্যবহার হয়েছে।',
        ]);

        $pending = Session::get('pending_registration');

        DB::transaction(function () use ($pending, $request) {
            $uplineId = null;
            if (!empty($pending['referral_code'])) {
                $uplineId = User::where('affiliate_id', $pending['referral_code'])->value('id');
            }

            // Create user as INACTIVE — awaiting admin approval
            $user = User::create([
                'name'           => $pending['name'],
                'username'       => $pending['username'],
                'password'       => $pending['password'],
                'upline_user_id' => $uplineId,
                'is_active'      => false,
                'joined_at'      => now(),
            ]);

            WalletAccount::create([
                'user_id'          => $user->id,
                'current_balance'  => 0,
                'hold_balance'     => 0,
                'total_earned'     => 0,
                'total_withdrawn'  => 0,
            ]);

            // Payment stored as pending — admin must approve
            RegistrationPayment::create([
                'user_id'                 => $user->id,
                'amount'                  => self::REGISTRATION_FEE,
                'currency'                => 'BDT',
                'payment_method'          => $request->payment_method,
                'gateway_name'            => 'manual',
                'gateway_transaction_id'  => $request->transaction_ref,
                'status'                  => 'pending',
                'raw_response'            => json_encode([
                    'transaction_ref' => $request->transaction_ref,
                    'message'         => 'Awaiting admin approval',
                ]),
            ]);
        });

        Session::forget('pending_registration');

        return redirect()->route('registration.pending');
    }

    public function distributeRegistrationCommission(User $newUser, int $paymentId): void
    {
        $settings = ReferralCommissionSetting::query()
            ->where('is_active', true)
            ->orderBy('generation_number')
            ->pluck('amount', 'generation_number')
            ->toArray();

        $defaultPlan = [
            1 => 50, 2 => 30, 3 => 20, 4 => 15, 5 => 12, 6 => 10, 7 => 8, 8 => 7,
            9 => 6, 10 => 5, 11 => 4, 12 => 4, 13 => 3, 14 => 3, 15 => 3, 16 => 2,
            17 => 2, 18 => 2, 19 => 2, 20 => 2, 21 => 1, 22 => 1, 23 => 1, 24 => 1,
        ];

        $plan = empty($settings) ? $defaultPlan : $settings;

        $current = $newUser->upline;
        $companyRemainder = 0;

        for ($generation = 1; $generation <= 24; $generation++) {
            $amount = (float) ($plan[$generation] ?? 0);
            if ($amount <= 0) {
                $current = $current?->upline;
                continue;
            }

            if ($current) {
                $wallet = WalletAccount::firstOrCreate(
                    ['user_id' => $current->id],
                    ['current_balance' => 0, 'hold_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
                );

                $before = (float) $wallet->current_balance;
                $wallet->current_balance = $before + $amount;
                $wallet->total_earned = (float) $wallet->total_earned + $amount;
                $wallet->save();

                WalletTransaction::create([
                    'user_id' => $current->id,
                    'type' => 'credit',
                    'source_type' => 'registration_referral',
                    'reference_type' => RegistrationPayment::class,
                    'reference_id' => $paymentId,
                    'amount' => $amount,
                    'balance_before' => $before,
                    'balance_after' => $before + $amount,
                    'status' => 'completed',
                    'note' => "Generation {$generation} referral commission",
                ]);

                ReferralCommission::create([
                    'earner_user_id' => $current->id,
                    'from_user_id' => $newUser->id,
                    'generation_number' => $generation,
                    'source_type' => 'registration',
                    'source_reference_id' => $paymentId,
                    'base_amount' => self::REGISTRATION_FEE,
                    'commission_amount' => $amount,
                    'status' => 'completed',
                ]);
            } else {
                $companyRemainder += $amount;
            }

            $current = $current?->upline;
        }

        $companyProfit = self::REGISTRATION_FEE - array_sum($plan);
        $companyGain = max(0, $companyProfit + $companyRemainder);

        if ($companyGain > 0) {
            $existing = AdminSetting::where('key_name', 'company_wallet_balance')->first();
            $balance = $existing ? (float) $existing->value_text : 0;

            AdminSetting::updateOrCreate(
                ['key_name' => 'company_wallet_balance'],
                ['value_text' => (string) ($balance + $companyGain)]
            );
        }
    }
}