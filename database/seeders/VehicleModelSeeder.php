<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleMake;
use App\Models\VehicleModel;

class VehicleModelSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            // Toyota
            ['Toyota', 'Corolla', '1990', '2024'],
            ['Toyota', 'Camry', '1990', '2024'],
            ['Toyota', 'RAV4', '1994', '2024'],
            ['Toyota', 'Hilux', '1968', '2024'],
            ['Toyota', 'Land Cruiser', '1951', '2024'],
            ['Toyota', 'Prius', '1997', '2024'],
            ['Toyota', 'Highlander', '2000', '2024'],
            ['Toyota', '4Runner', '1984', '2024'],
            ['Toyota', 'Tacoma', '1995', '2024'],
            ['Toyota', 'Sienna', '1997', '2024'],
            
            // Honda
            ['Honda', 'Civic', '1972', '2024'],
            ['Honda', 'Accord', '1976', '2024'],
            ['Honda', 'CR-V', '1995', '2024'],
            ['Honda', 'Pilot', '2002', '2024'],
            ['Honda', 'Odyssey', '1994', '2024'],
            ['Honda', 'Fit', '2001', '2024'],
            ['Honda', 'Ridgeline', '2005', '2024'],
            
            // Nissan
            ['Nissan', 'Altima', '1992', '2024'],
            ['Nissan', 'Sentra', '1982', '2024'],
            ['Nissan', 'Rogue', '2007', '2024'],
            ['Nissan', 'Pathfinder', '1986', '2024'],
            ['Nissan', 'Frontier', '1997', '2024'],
            ['Nissan', 'Titan', '2003', '2024'],
            
            // Ford
            ['Ford', 'F-150', '1948', '2024'],
            ['Ford', 'Mustang', '1964', '2024'],
            ['Ford', 'Explorer', '1990', '2024'],
            ['Ford', 'Escape', '2000', '2024'],
            ['Ford', 'Focus', '1998', '2024'],
            ['Ford', 'Fusion', '2005', '2024'],
            
            // Chevrolet
            ['Chevrolet', 'Silverado', '1998', '2024'],
            ['Chevrolet', 'Tahoe', '1992', '2024'],
            ['Chevrolet', 'Equinox', '2004', '2024'],
            ['Chevrolet', 'Malibu', '1964', '2024'],
            ['Chevrolet', 'Cruze', '2008', '2024'],
        ];

        foreach ($models as $model) {
            $make = VehicleMake::where('make_name', $model[0])->first();
            if ($make) {
                VehicleModel::firstOrCreate(
                    [
                        'vehicle_make_id' => $make->id,
                        'model_name' => $model[1],
                    ],
                    [
                        'year_start' => $model[2],
                        'year_end' => $model[3],
                    ]
                );
            }
        }
    }
}
