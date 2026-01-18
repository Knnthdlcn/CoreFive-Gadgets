<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventAdminFromWeb
{
    /**
     * Ensure admin accounts are never authenticated on the default (web) guard.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();

        if ($user && (($user->role ?? 'customer') === 'admin')) {
            Auth::guard('web')->logout();

            // Keep the session intact (admin guard may be using it).
            $request->session()->forget('password_hash_web');
        }

        return $next($request);
    }
}
