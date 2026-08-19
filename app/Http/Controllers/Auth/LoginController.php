<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->banned_at) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'এই account টি admin দ্বারা banned করা হয়েছে।'
                ])->withInput();
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'আপনার account এখনও active হয়নি। Admin approval এর অপেক্ষায় আছে।'
                ])->withInput();
            }

            // Update last login
            \App\Models\User::whereKey($user->id)->update(['last_login_at' => now()]);

            $request->session()->regenerate();
            return redirect()->intended(route('user.home'));
        }

        return back()->withErrors([
            'username' => 'Username বা Password সঠিক নয়।'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}