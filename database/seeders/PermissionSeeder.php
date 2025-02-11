<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'receiving dashboard'],
            ['name' => 'receiving unloading'],
            ['name' => 'receiving washing'],
            ['name' => 'receiving rejectionlog'],
            ['name' => 'ripening dashboard'],
            ['name' => 'cold storage'],
            ['name' => 'ripening rooms'],
            ['name' => 'sorting'],
            ['name' => 'sort washing'],
            ['name' => 'processing dashboard'],
            ['name' => 'processing'],
            ['name' => 'cooking'],
            ['name' => 'process washing'],
            ['name' => 'packing dashboard'],
            ['name' => 'packing'],
            ['name' => 'rework'],
            ['name' => 'green room'],
            ['name' => 'refrigeration'],
            ['name' => 'packing washing'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
