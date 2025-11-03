<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Engine Parts', 'description' => 'Engine components and accessories'],
            ['name' => 'Brake System', 'description' => 'Brake pads, discs, and related components'],
            ['name' => 'Suspension', 'description' => 'Shocks, struts, and suspension parts'],
            ['name' => 'Electrical', 'description' => 'Batteries, alternators, and electrical components'],
            ['name' => 'Cooling System', 'description' => 'Radiators, water pumps, and cooling parts'],
            ['name' => 'Transmission', 'description' => 'Transmission components and fluids'],
            ['name' => 'Exhaust System', 'description' => 'Mufflers, pipes, and exhaust components'],
            ['name' => 'Filters', 'description' => 'Air, oil, and fuel filters'],
            ['name' => 'Belts & Hoses', 'description' => 'Timing belts, serpentine belts, and hoses'],
            ['name' => 'Lights & Bulbs', 'description' => 'Headlights, tail lights, and bulbs'],
            ['name' => 'Body Parts', 'description' => 'Bumpers, fenders, and body panels'],
            ['name' => 'Interior Parts', 'description' => 'Seats, dashboards, and interior components'],
            ['name' => 'Wheels & Tires', 'description' => 'Rims, tires, and wheel accessories'],
            ['name' => 'Steering', 'description' => 'Steering wheels, racks, and related parts'],
            ['name' => 'Fuel System', 'description' => 'Fuel pumps, injectors, and fuel system parts'],
            ['name' => 'Ignition System', 'description' => 'Spark plugs, coils, and ignition components'],
            ['name' => 'Oil & Fluids', 'description' => 'Engine oil, transmission fluid, and lubricants'],
            ['name' => 'Gaskets & Seals', 'description' => 'Gaskets, seals, and O-rings'],
            ['name' => 'Sensors', 'description' => 'Oxygen sensors, temperature sensors, and more'],
            ['name' => 'Clutch System', 'description' => 'Clutch plates, pressure plates, and related parts'],
            ['name' => 'Drive Shaft', 'description' => 'CV joints, drive shafts, and axles'],
            ['name' => 'Timing Components', 'description' => 'Timing chains, gears, and components'],
            ['name' => 'Valve Train', 'description' => 'Valves, springs, and valve train parts'],
            ['name' => 'Pistons & Rings', 'description' => 'Pistons, rings, and cylinder components'],
            ['name' => 'Camshaft', 'description' => 'Camshafts and related components'],
            ['name' => 'Crankshaft', 'description' => 'Crankshafts and bearings'],
            ['name' => 'Oil Pump', 'description' => 'Oil pumps and related components'],
            ['name' => 'Water Pump', 'description' => 'Water pumps and gaskets'],
            ['name' => 'Thermostat', 'description' => 'Thermostats and housing'],
            ['name' => 'Radiator', 'description' => 'Radiators and cooling fans'],
            ['name' => 'AC Components', 'description' => 'AC compressors, condensers, and parts'],
            ['name' => 'Wiper System', 'description' => 'Wiper blades, motors, and arms'],
            ['name' => 'Mirrors', 'description' => 'Side mirrors and rearview mirrors'],
            ['name' => 'Weatherstripping', 'description' => 'Door seals and weatherstripping'],
            ['name' => 'Fasteners', 'description' => 'Bolts, nuts, screws, and clips'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
