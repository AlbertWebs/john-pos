<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleMake;

class VehicleMakeSeeder extends Seeder
{
    public function run(): void
    {
        // Vehicle makes popular in Kenya
        $makes = [
            ['make_name' => 'Toyota'], // Very popular in Kenya
            ['make_name' => 'Nissan'], // Very popular in Kenya
            ['make_name' => 'Mazda'], // Popular in Kenya
            ['make_name' => 'Subaru'], // Popular in Kenya
            ['make_name' => 'Mitsubishi'], // Popular in Kenya
            ['make_name' => 'Suzuki'], // Popular in Kenya
            ['make_name' => 'Isuzu'], // Popular in Kenya
            ['make_name' => 'Honda'], // Popular in Kenya
            ['make_name' => 'Ford'], // Popular in Kenya
            ['make_name' => 'Mercedes-Benz'], // Common in Kenya
            ['make_name' => 'BMW'], // Common in Kenya
            ['make_name' => 'Volkswagen'], // Common in Kenya
            ['make_name' => 'Peugeot'], // Common in Kenya
            ['make_name' => 'Hyundai'], // Common in Kenya
            ['make_name' => 'Kia'], // Common in Kenya
            ['make_name' => 'Land Rover'], // Common in Kenya
            ['make_name' => 'Range Rover'], // Common in Kenya
            ['make_name' => 'Audi'], // Less common but present
            ['make_name' => 'Volvo'], // Less common but present
            ['make_name' => 'Jeep'], // Less common but present
            ['make_name' => 'Daihatsu'], // Less common but present
            ['make_name' => 'Lexus'], // Less common but present
            ['make_name' => 'Chevrolet'], // Less common but present
            ['make_name' => 'Renault'], // Less common but present
        ];

        foreach ($makes as $make) {
            VehicleMake::firstOrCreate(
                ['make_name' => $make['make_name']],
                $make
            );
        }
    }
}
