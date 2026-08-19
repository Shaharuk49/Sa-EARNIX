<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->banned_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'এই account টি admin দ্বারা banned করা হয়েছে.',
            ]);
        }

        return $next($request);
    }
}