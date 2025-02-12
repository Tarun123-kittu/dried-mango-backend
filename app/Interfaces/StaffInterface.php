<?php

namespace App\Interfaces;
use App\Http\Requests\Staff\CreateStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;

interface StaffInterface
{
    public function getAllStaff();
    public function createStaff(CreateStaffRequest $request);
    public function updateStaff(UpdateStaffRequest $request, $id);
    public function deleteStaff($id);
}
