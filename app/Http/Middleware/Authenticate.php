<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
//    protected function redirectTo(Request $request): ?string
//    {
//        return $request->expectsJson() ? null : route('login');
//    }

    public function handle(Request $request, \Closure $next): Response
    {
        if (auth()->check()) {
            $response = $next($request);
            return $response;
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
