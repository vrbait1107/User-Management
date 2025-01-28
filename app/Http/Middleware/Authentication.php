<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $apiKey = env('AUTHENTICATION_API_KEY');
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token not provided'], 400);
        }

        if ($token !== $apiKey) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        return $next($request);

    }
}
