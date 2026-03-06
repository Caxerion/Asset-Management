<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $peminjam = Role::firstOrCreate(['name' => 'peminjam'], ['label' => 'Peminjam']);
        $read   = Role::firstOrCreate(['name' => 'read'], ['label' => 'Read Only']);
        // $staff = Role::firstOrCreate(['name' => 'staff'], ['label' => 'Staff']);
        // $pic   = Role::firstOrCreate(['name' => 'pic'], ['label' => 'Person In Charge']);

        // Assign roles to users
        $adminUser = User::where('email', 'admin@gudang.test')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

         $peminjamUser = User::where('email', 'peminjam@gudang.test')->first();
        if ($peminjamUser) {
            $peminjamUser->assignRole('peminjam');
        }

        // $picUsers = User::whereIn('email', [
        //     'picLM@example.test',
        //     'picL1@example.test',
        //     'picL2@example.test',
        //     'picL3@example.test'
        // ])->get();
        // foreach ($picUsers as $picUser) {
        //     $picUser->assignRole('pic');
        // }

        // $kasirUser = User::where('email', 'kasir@gudang.test')->first();
        // if ($kasirUser) {
        //     $kasirUser->assignRole('staff'); // Assuming kasir is staff
        // }

        $readUser = User::where('email', 'read@gudang.test')->first();
        if ($readUser) {
            $readUser->assignRole('read');
        }
    }
}