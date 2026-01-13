<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfUnauthenticatedCart
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('home')
                ->with('error', 'Please sign in to view your cart.')
                ->with('show_login', true);
        }

        return $next($request);
    }
}
