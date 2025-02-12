<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Http\Resources\StaffResource;
use App\Interfaces\StaffInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffInterface $staffService)
    {
        $this->staffService = $staffService;
    }

    public function index(Request $request)
    {
        try {
            $staff = $this->staffService->getAllStaff();
            return ApiResponseClass::sendResponse($staff, __('messages.staff_listed'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function store(CreateStaffRequest $request)
    {
        DB::beginTransaction();
        try {
            $staff = $this->staffService->createStaff($request);
            DB::commit();
            return ApiResponseClass::sendResponse($staff, __('messages.staff_created'), 201);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function update(UpdateStaffRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $staff = $this->staffService->updateStaff($request, $id);
            return $staff;
            DB::commit();
            return ApiResponseClass::sendResponse($staff, __('messages.staff_updated'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $staff = $this->staffService->deleteStaff($id);
            DB::commit();
            return ApiResponseClass::sendResponse(null, __('messages.staff_deleted'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return $e->getMessage();
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::where('id', $id)->where('role_id', '!=', 1)->first();

            if (!$user) {
                return ApiResponseClass::throw(__('messages.user_not_found'), 404);
            }

            return ApiResponseClass::sendResponse(new StaffResource($user), __('messages.staff_fetched'), 200);
        } catch (HttpResponseException $ex) {
            throw $ex;
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }
}
