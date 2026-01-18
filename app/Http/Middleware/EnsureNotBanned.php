<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNotBanned
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if (!empty($user?->banned_at)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = 'Your account has been temporarily disabled (banned). Please contact Customer Service for help.';

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'error' => $message,
                        'banned' => true,
                        'redirect_url' => route('account.disabled'),
                    ], 403);
                }

                return redirect()->route('account.disabled')->with('error', $message);
            }
        }

        return $next($request);
    }
}
