<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReferralCommissionService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    protected $commissionService;

    public function __construct(ReferralCommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function registerUser(Request $request)
    {
        // Validate and create the user (registration logic)

        $user = User::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            // Other fields...
        ]);

        // Process referral commission upon successful registration
        $this->commissionService->processRegistrationCommission($user);

        // Return response after registration (like redirect or success message)
    }
}