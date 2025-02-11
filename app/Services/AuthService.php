<?php

namespace App\Services;

use App\Interfaces\AuthInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\AuthResource;

class AuthService implements AuthInterface
{
    public function login($request)
    {
        $user = User::select('id', 'name', 'email', 'password', 'role_id')
        ->with([
            'role:id,name'
        ])
        ->where('email', $request->email)
        ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new \Exception(__('messages.invalid_credentials'));
        }

        $roleName = optional($user->role)->name ?? 'No Role';

        $customClaims = [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $roleName,
        ];

        try {
            $token = JWTAuth::claims($customClaims)->fromUser($user);
            $user->token = $token;
            return new AuthResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token generation failed', 'message' => $e->getMessage()], 500);
        }
    }
}
