<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Interfaces\AuthInterface;
use App\Classes\ApiResponseClass;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private AuthInterface $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function login(LoginRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->authInterface->login($request);
            DB::commit();
            return ApiResponseClass::sendResponse(new AuthResource($data), __('messages.login_success'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $this->authInterface->sendResetEmail($request->email);

            DB::commit();
            return ApiResponseClass::sendResponse(null,__('messages.reset_link'), 200);
            
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $this->authInterface->resetPassword($request->email, $request->token, $request->password);
            DB::commit();
            return ApiResponseClass::sendResponse(null,__('messages.password_reset_successfull'), 200);
            
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }

    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!Hash::check($request->current_password, $user->password)) {
                return ApiResponseClass::throw(__('messages.incorrect_current_password'), 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return ApiResponseClass::sendResponse(null,__('messages.password_updated'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        }
        catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function test(Request $request)
    {
        return $request->user();
    }
}
