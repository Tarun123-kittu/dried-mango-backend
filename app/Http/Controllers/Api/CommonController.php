<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Interfaces\RoleServiceInterface;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    private $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    public function getRoles()
    {
        try {
            $roles = $this->roleService->getAllRoles();
            return ApiResponseClass::sendResponse($roles, __('messages.roles_fetched_successfully'), 200);
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }
}
