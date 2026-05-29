<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyStorefrontToken
{
    public function handle(Request $request, Closure $next)
    {
        $expected = env('STOREFRONT_API_TOKEN');

        if ($expected && ! hash_equals($expected, (string) $request->header('X-Storefront-Token'))) {
            return response()->json(['message' => 'Storefront token invalido'], 401);
        }

        return $next($request);
    }
}
