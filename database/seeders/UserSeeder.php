<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only 2 roles: admin and read-only
        $admin = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $read = Role::firstOrCreate(['name' => 'read'], ['label' => 'Read Only']);

        // Create admin user with full access (create, update, delete, read)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gudang.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('adminBARANG!'),
            ]
        );
        $adminUser->assignRole('admin');

        // Create read-only user (can only read)
        $readUser = User::firstOrCreate(
            ['email' => 'read@gudang.test'],
            [
                'name' => 'Read Only User',
                'password' => Hash::make('ReadBARANG!'),
            ]
        );
        $readUser->assignRole('read');
    }
}
