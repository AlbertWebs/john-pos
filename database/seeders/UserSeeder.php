<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'pin' => Hash::make('8945'), // PIN: 1234
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Cashier
        $cashier = User::firstOrCreate(
            ['username' => 'cashier'],
            [
                'name' => 'Cashier',
                'pin' => Hash::make('5678'), // PIN: 5678
                'role' => 'cashier',
                'status' => 'active',
            ]
        );
        $cashier->assignRole('cashier');
    }
}
