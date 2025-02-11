<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'receiving manager'],
            ['name' => 'ripening & selection manager'],
            ['name' => 'processing cooking manager'],
            ['name' => 'packing manager'],
            ['name' => 'supplier'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
