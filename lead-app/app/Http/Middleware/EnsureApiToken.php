<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiToken
{
    /**
     * Simple machine-to-machine auth between n8n and Laravel.
     * The token lives in .env as LEAD_API_TOKEN.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.lead_api.token');

        if (! $expected || ! hash_equals($expected, (string) $request->bearerToken())) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
