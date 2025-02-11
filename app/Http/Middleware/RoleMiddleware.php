<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Classes\ApiResponseClass;
use App\Enums\RoleEnum;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user===null) {
                return ApiResponseClass::throw(__('messages.unauthorized'), 401);
            }

            $allowedRoles = array_intersect($roles, RoleEnum::getValues());

            if (!in_array($user->role->name, $allowedRoles)) {
                return ApiResponseClass::throw(__('messages.unauthorized'), 403);
            }

            return $next($request);
        } catch (HttpResponseException $ex) {
            throw $ex;
        }
    }
}
