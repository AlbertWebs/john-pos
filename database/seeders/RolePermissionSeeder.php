<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage inventory',
            'view sales',
            'create sales',
            'edit sales',
            'delete sales',
            'view reports',
            'manage users',
            'manage settings',
            'view customers',
            'manage customers',
            'manage returns',
            'process payments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);

        // Assign all permissions to super_admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign limited permissions to cashier
        $cashierRole->givePermissionTo([
            'view sales',
            'create sales',
            'view customers',
            'manage customers',
            'process payments',
            'manage inventory', // Can add stock
        ]);
    }
}
