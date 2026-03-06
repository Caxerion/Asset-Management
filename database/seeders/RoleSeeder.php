<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Only 2 roles: admin and read-only
        $admin = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $read   = Role::firstOrCreate(['name' => 'read'], ['label' => 'Read Only']);

        // Assign roles to users
        $adminUser = User::where('email', 'admin@gudang.test')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $readUser = User::where('email', 'read@gudang.test')->first();
        if ($readUser) {
            $readUser->assignRole('read');
        }
    }
}