<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowStorefrontCors
{
    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');
        $allowedOrigins = array_filter(array_map('trim', explode(',', (string) env('STOREFRONT_ALLOWED_ORIGINS', '*'))));
        $allowsAnyOrigin = in_array('*', $allowedOrigins, true);
        $allowOrigin = $allowsAnyOrigin ? '*' : ($origin && in_array($origin, $allowedOrigins, true) ? $origin : null);

        if ($origin && ! $allowOrigin) {
            return response()->json(['message' => 'Storefront origin no permitido'], 403);
        }

        $headers = [
            'Access-Control-Allow-Origin' => $allowOrigin ?: '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, X-Storefront-Token',
            'Access-Control-Max-Age' => '86400',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response('', 204, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
