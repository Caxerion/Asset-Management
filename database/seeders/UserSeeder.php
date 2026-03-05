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
        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $peminjam = Role::firstOrCreate(['name' => 'peminjam'], ['label' => 'Peminjam']);
        $read = Role::firstOrCreate(['name' => 'read'], ['label' => 'Read Only']);
        $test = Role::firstOrCreate(['name' => 'test'], ['label' => 'Test']);

        // Create demo users with passwords
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gudang.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('adminBARANG!'),
            ]
        );
        $adminUser->assignRole('admin');

        $peminjamUser = User::firstOrCreate(
            ['email' => 'peminjam@gudang.test'],
            [
                'name' => 'Peminjam User',
                'password' => Hash::make('PeminjamBARANG!'),
            ]
        );
        $peminjamUser->assignRole('peminjam');

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
