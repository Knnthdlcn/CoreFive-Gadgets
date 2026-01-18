<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearBuyNowOnNavigation
{
    /**
     * Clear any pending buy-now session item when user navigates away from checkout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() === 'GET' && $request->session()->has('buy_now')) {
            // Do not clear for background/AJAX/JSON requests (checkout page polls /cart/get).
            if ($request->expectsJson() || $request->ajax()) {
                return $next($request);
            }

            $routeName = $request->route()?->getName();
            $allowed = [
                'checkout.index',
            ];

            if (!in_array($routeName, $allowed, true)) {
                $request->session()->forget('buy_now');
            }
        }

        return $next($request);
    }
}
