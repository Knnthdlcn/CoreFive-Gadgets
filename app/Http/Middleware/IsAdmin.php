<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated using the admin guard
        if (auth('admin')->check()) {
            return $next($request);
        }

        // If not authenticated as admin, redirect to admin login
        return redirect()->route('admin.login')->with('error', 'Please login as admin to access this area');
    }
}
