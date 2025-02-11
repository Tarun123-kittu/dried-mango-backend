<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Interfaces\AuthInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
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
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex->getMessage());
        }
    }

}
