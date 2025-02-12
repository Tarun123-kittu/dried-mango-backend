<?php

namespace App\Services;

use App\Models\Role;
use App\Http\Resources\RoleResource;
use App\Interfaces\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{
    public function getAllRoles()
    {
        return RoleResource::collection(Role::select('id', 'name') ->whereNotIn('name', ['admin', 'supplier'])->get());
    }
}