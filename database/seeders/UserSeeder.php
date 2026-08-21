<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $customerRole = Role::where('slug', 'customer')->first();

        // 1. Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@busticket.test'],
            [
                'name' => 'Super Admin',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
            ]
        );

        // 2. Admin
        User::updateOrCreate(
            ['email' => 'admin@busticket.test'],
            [
                'name' => 'Admin BusGo',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        // 3. Customer
        User::updateOrCreate(
            ['email' => 'customer@busticket.test'],
            [
                'name' => 'Customer Demo',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
                'role_id' => $customerRole->id,
            ]
        );

        $this->command->info('Users created with password: password');
    }
}
