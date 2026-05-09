<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage banks',
            'manage bank branches',
            'manage templates',
            'manage template fields',
            'manage users',
            'manage documents',
            'manage signatures',
            'manage roles',
            'manage permissions',
            'view download logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'view dashboard',
            'manage banks',
            'manage bank branches',
            'manage templates',
            'manage template fields',
            'manage documents',
            'manage signatures',
            'view download logs',
        ]);

        $operator = Role::firstOrCreate(['name' => 'Operator', 'guard_name' => 'web']);
        $operator->syncPermissions([
            'view dashboard',
            'manage documents',
            'manage signatures',
        ]);
    }
}
