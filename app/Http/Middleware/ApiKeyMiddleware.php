<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json(['message' => 'Unauthorized: API Key is missing.'], 401);
        }

        $apiKeyRecord = ApiKey::where('key', $apiKey)->where('is_active', true)->first();
        if (!$apiKeyRecord) {
            return response()->json(['message' => 'Unauthorized: Invalid API Key.'], 401);
        }

        return $next($request);
    }
    protected $middlewareAliases = [
        'auth.apikey' => \App\Http\Middleware\ApiKeyMiddleware::class,
    ];
}
