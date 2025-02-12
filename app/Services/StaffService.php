<?php

namespace App\Services;

use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Interfaces\StaffInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Classes\ApiResponseClass;
use App\Http\Resources\StaffResource;
use App\Models\Role;
use App\Notifications\StaffPasswordSetupNotification;

class StaffService implements StaffInterface
{
    public function getAllStaff()
    {
        $staff = User::where('role_id', '!=', 1)->paginate(10);
        return [
            'data' => StaffResource::collection($staff)->resolve(),
            'pagination' => [
                'per_page' => $staff->perPage(),
                'total' => $staff->total(),
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
            ]
        ];
    }

    public function createStaff(CreateStaffRequest $request)
    {

        $user = User::select('id', 'name', 'email', 'password')
            ->where('email', $request->email)
            ->first();

        if ($user) {
            return ApiResponseClass::throw(__('messages.user_exist'), 409);
        }

        $role = Role::where('id', $request->role_id)->first();
        if ($role->name == 'admin') {
            ApiResponseClass::throw(__('messages.role_not_found'), 404);
        }

        $token = Str::random(60);
        try {
            $staff = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'gender' => $request->gender,
                'address' => $request->address,
                'status' => 1,
                'role_id' => $request->role_id,
                'password' => Hash::make(Str::random(12)),
            ]);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $staff->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );


            $staff->notify(new StaffPasswordSetupNotification($token));
            return new StaffResource($staff);
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }


        return new StaffResource($staff);
    }

    public function updateStaff(UpdateStaffRequest $request, $id)
    {

        $staff = User::where('id',$id)->first();
        if (!$staff) {
            return ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }
        $role = Role::where('id', $request->role_id)->first();

        if ($role && $role->name == 'admin') {
            return ApiResponseClass::throw(__('messages.role_not_found'), 404);
        }
        try {
            $staff->update($request->validated());
            return new StaffResource($staff);
        } catch (\Exception $e) {
            return ApiResponseClass::throw(__('messages.something_went_wrong'), 500);
        }
    }

    public function deleteStaff($id)
    {
        $staff = User::where('id',$id)->first();

        if(!$staff) {
            return ApiResponseClass::throw(__('messages.user_not_found'), 404);
        }

        $staff->delete();
        return ApiResponseClass::sendResponse(null, __('messages.staff_deleted'), 200);
    }
}
