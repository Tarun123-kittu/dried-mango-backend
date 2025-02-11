<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define role permissions mapping
        $rolePermissions = [
            'admin' => [  // Admin gets all permissions
                'receiving dashboard', 'receiving unloading', 'receiving washing', 'receiving rejectionlog',
                'ripening dashboard', 'cold storage', 'ripening rooms', 'sorting', 'sort washing',
                'processing dashboard', 'processing', 'cooking', 'process washing',
                'packing dashboard', 'packing', 'rework', 'green room', 'refrigeration', 'packing washing',
            ],
            'receiving manager' => ['receiving dashboard', 'receiving unloading', 'receiving washing', 'receiving rejectionlog'],
            'ripening & selection manager' => ['ripening dashboard', 'cold storage', 'ripening rooms', 'sorting', 'sort washing'],
            'processing cooking manager' => ['processing dashboard', 'processing', 'cooking', 'process washing'],
            'packing manager' => ['packing dashboard', 'packing', 'rework', 'green room', 'refrigeration', 'packing washing'],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                foreach ($permissions as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();

                    if ($permission) {
                        RolePermission::create([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id,
                            'can_view' => true,
                            'can_read' => true,
                            'can_create' => true,
                            'can_update' => true,
                            'can_delete' => true,
                            'all' => ($roleName === 'admin') // Admin gets full access
                        ]);
                    }
                }
            }
        }
    }
}
