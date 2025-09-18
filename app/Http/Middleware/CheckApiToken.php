<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();         // from Authorization: Bearer <token>
        $customToken = $request->header('X-Api-Token'); // custom header token

        // First check Sanctum token if exists and valid
        if ($bearerToken && Auth::guard('api')->check()) {
            // Sanctum token valid, allow request
            return $next($request);
        }

        // If no sanctum token, check custom API token
        if ($customToken) {
            $apiToken = \App\Models\ApiToken::where('token', $customToken)->first();

            if (!$apiToken) {
                return response()->json(['error' => 'Invalid Custom API Token'], 403);
            }

            // Log the request
            \App\Models\ApiTokenLog::create([
                'api_token_id' => $apiToken->id,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'request_data' => $request->except(['password', 'token']), // avoid sensitive data
            ]);

            // Share in request
            $request->merge(['api_token' => $apiToken]);

            return $next($request);
        }

        // Neither Sanctum nor Custom token present/valid
        return response()->json(['error' => 'Unauthorized. Token missing or invalid'], 401);
    }
}
