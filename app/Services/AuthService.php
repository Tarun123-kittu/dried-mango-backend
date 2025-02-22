<?php

namespace App\Services;

use App\Interfaces\AuthInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\AuthResource;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;

class AuthService implements AuthInterface
{
    public function login($request)
    {
        $user = User::select('id', 'name', 'email', 'password', 'role_id')
            ->with(['role:id,name'])
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            ApiResponseClass::throw(__('messages.invalid_credentials'), 401);
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
            ApiResponseClass::throw(__('messages.token_generation_fail'), 500);
        }
    }

    public function sendResetEmail($email)
    {

        $user = User::where('email', $email)->first();
        if (!$user) {
            return ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }

        $plainToken = Str::random(60);
        $hashedToken = Hash::make($plainToken);
        try {

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                ['token' => $hashedToken, 'created_at' => now()]
            );

            try {
                $user->notify(new ResetPasswordNotification($plainToken));
            } catch (\Exception $e) {
                return ApiResponseClass::throw(__('messages.mail_failed'), 500);
            }

            return true;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function resetPassword($email, $token, $password)
    {
        $resetRequest = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$resetRequest || !Hash::check($token, $resetRequest->token)) {
            ApiResponseClass::throw(__('messages.request_not_found'), 404);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make($password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return true;
        } else {
            ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }

        return true;
    }
}
