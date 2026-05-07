<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OfficialLink;
use App\Models\ReferralCommission;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Show user home page with personal info, affiliate data, and dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $walletAccount = WalletAccount::firstOrCreate(
            ['user_id' => $user->id],
            ['current_balance' => 0, 'hold_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        $directReferrals = $user->downlines()->where('is_active', true)->count();
        $totalTeam = $this->getTotalTeamCount($user);

        $referralLink = route('register', ['ref' => $user->affiliate_id]);

        $officialLinks = OfficialLink::where('is_active', true)->get();

        return view('user.home', compact(
            'user',
            'walletAccount',
            'directReferrals',
            'totalTeam',
            'referralLink',
            'officialLinks'
        ));
    }

    /**
     * Get total team count (all downline generation).
     */
    private function getTotalTeamCount($user)
    {
        $count = 0;
        foreach ($user->downlines as $downline) {
            $count += 1 + $this->getTotalTeamCount($downline);
        }
        return $count;
    }

    /**
     * Show personal information edit page.
     */
    public function personalInfo()
    {
        $user = Auth::user();
        return view('user.personal-info', compact('user'));
    }

    /**
     * Update personal information.
     */
    public function updatePersonalInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $data = $request->only(['name', 'email', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Personal information updated successfully.');
    }
}
