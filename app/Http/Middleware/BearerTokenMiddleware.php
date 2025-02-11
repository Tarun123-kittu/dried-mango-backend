<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Classes\ApiResponseClass;

class BearerTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user === null) {
                return ApiResponseClass::throw('Unauthorized: Token missing or invalid', 401);
            }
            return $next($request);
        } catch (\Exception $e) {
            return ApiResponseClass::throw('Unauthorized: Token missing or invalid', 401);
        }
    }
}
