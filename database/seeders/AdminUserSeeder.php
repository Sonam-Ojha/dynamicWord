<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'phone' => '0000000000',
                'password' => Hash::make('password'),
                'status' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['Super Admin']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'phone' => '1111111111',
                'password' => Hash::make('password'),
                'status' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Admin']);

        $operator = User::updateOrCreate(
            ['email' => 'operator@example.com'],
            [
                'name' => 'Operator User',
                'phone' => '2222222222',
                'password' => Hash::make('password'),
                'status' => true,
                'email_verified_at' => now(),
            ]
        );
        $operator->syncRoles(['Operator']);
    }
}
