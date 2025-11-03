<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleMake;

class VehicleMakeSeeder extends Seeder
{
    public function run(): void
    {
        $makes = [
            ['make_name' => 'Toyota'],
            ['make_name' => 'Honda'],
            ['make_name' => 'Nissan'],
            ['make_name' => 'Mazda'],
            ['make_name' => 'Subaru'],
            ['make_name' => 'Mitsubishi'],
            ['make_name' => 'Suzuki'],
            ['make_name' => 'Isuzu'],
            ['make_name' => 'Ford'],
            ['make_name' => 'Chevrolet'],
            ['make_name' => 'BMW'],
            ['make_name' => 'Mercedes-Benz'],
            ['make_name' => 'Volkswagen'],
            ['make_name' => 'Audi'],
            ['make_name' => 'Volvo'],
            ['make_name' => 'Peugeot'],
            ['make_name' => 'Renault'],
            ['make_name' => 'Hyundai'],
            ['make_name' => 'Kia'],
            ['make_name' => 'Land Rover'],
            ['make_name' => 'Range Rover'],
            ['make_name' => 'Jeep'],
            ['make_name' => 'Dodge'],
            ['make_name' => 'Chrysler'],
            ['make_name' => 'Daihatsu'],
            ['make_name' => 'Lexus'],
            ['make_name' => 'Infiniti'],
            ['make_name' => 'Acura'],
            ['make_name' => 'Cadillac'],
            ['make_name' => 'Buick'],
            ['make_name' => 'GMC'],
            ['make_name' => 'Ram'],
            ['make_name' => 'Lincoln'],
            ['make_name' => 'Jaguar'],
            ['make_name' => 'Porsche'],
        ];

        foreach ($makes as $make) {
            VehicleMake::firstOrCreate(
                ['make_name' => $make['make_name']],
                $make
            );
        }
    }
}
