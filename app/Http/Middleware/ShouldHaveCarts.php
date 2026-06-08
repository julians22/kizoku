<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShouldHaveCarts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if user has carts, if not redirect to home page with error message
        // get carts from session
        $carts = $request->session()->get('cart', []);

        if (empty($carts)) {
            return redirect()->route('home')
                ->with('error', 'Your cart is empty. Please add some products to your cart before checkout.');
        }

        return $next($request);
    }
}
