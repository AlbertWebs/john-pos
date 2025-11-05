<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleMake;
use App\Models\VehicleModel;

class VehicleModelSeeder extends Seeder
{
    public function run(): void
    {
        // Vehicle models popular in Kenya
        $models = [
            // Toyota (Very popular in Kenya)
            ['Toyota', 'Hilux', '1968', '2024'], // Extremely popular
            ['Toyota', 'Land Cruiser', '1951', '2024'], // Very popular
            ['Toyota', 'Corolla', '1990', '2024'], // Very popular
            ['Toyota', 'Camry', '1990', '2024'], // Very popular
            ['Toyota', 'RAV4', '1994', '2024'], // Popular
            ['Toyota', 'Probox', '2002', '2024'], // Very popular in Kenya
            ['Toyota', 'Succeed', '2002', '2024'], // Popular in Kenya
            ['Toyota', 'Passo', '2004', '2024'], // Popular in Kenya
            ['Toyota', 'Vitz', '1999', '2024'], // Popular in Kenya
            ['Toyota', 'Fielder', '2001', '2024'], // Popular in Kenya
            
            // Nissan (Very popular in Kenya)
            ['Nissan', 'X-Trail', '2000', '2024'], // Very popular
            ['Nissan', 'Patrol', '1951', '2024'], // Very popular
            ['Nissan', 'Note', '2004', '2024'], // Popular
            ['Nissan', 'March', '1982', '2024'], // Popular
            ['Nissan', 'Almera', '1995', '2024'], // Popular
            ['Nissan', 'Sunny', '1966', '2024'], // Popular
            ['Nissan', 'Tiida', '2004', '2024'], // Popular
            ['Nissan', 'Navara', '1997', '2024'], // Popular
            
            // Mazda (Popular in Kenya)
            ['Mazda', 'Demio', '1996', '2024'], // Very popular
            ['Mazda', 'Axela', '2003', '2024'], // Popular
            ['Mazda', 'CX-5', '2012', '2024'], // Popular
            ['Mazda', 'BT-50', '2006', '2024'], // Popular
            ['Mazda', 'Premacy', '1999', '2024'], // Popular
            
            // Subaru (Popular in Kenya)
            ['Subaru', 'Forester', '1997', '2024'], // Popular
            ['Subaru', 'Outback', '1994', '2024'], // Popular
            ['Subaru', 'Impreza', '1992', '2024'], // Popular
            ['Subaru', 'Legacy', '1989', '2024'], // Popular
            
            // Mitsubishi (Popular in Kenya)
            ['Mitsubishi', 'Pajero', '1982', '2024'], // Very popular
            ['Mitsubishi', 'Lancer', '1973', '2024'], // Popular
            ['Mitsubishi', 'Outlander', '2001', '2024'], // Popular
            ['Mitsubishi', 'Triton', '1978', '2024'], // Popular
            
            // Suzuki (Popular in Kenya)
            ['Suzuki', 'Vitara', '1988', '2024'], // Popular
            ['Suzuki', 'Swift', '1983', '2024'], // Popular
            ['Suzuki', 'SX4', '2006', '2024'], // Popular
            ['Suzuki', 'Grand Vitara', '1998', '2024'], // Popular
            
            // Isuzu (Popular in Kenya)
            ['Isuzu', 'D-Max', '2002', '2024'], // Popular
            ['Isuzu', 'Trooper', '1981', '2024'], // Popular
            
            // Honda (Popular in Kenya)
            ['Honda', 'Civic', '1972', '2024'], // Popular
            ['Honda', 'CR-V', '1995', '2024'], // Popular
            ['Honda', 'Accord', '1976', '2024'], // Popular
            
            // Ford (Common in Kenya)
            ['Ford', 'Ranger', '1998', '2024'], // Popular
            ['Ford', 'Everest', '2003', '2024'], // Popular
            ['Ford', 'Explorer', '1990', '2024'], // Popular
            ['Ford', 'Focus', '1998', '2024'], // Popular
            
            // Mercedes-Benz (Common in Kenya)
            ['Mercedes-Benz', 'C-Class', '1993', '2024'],
            ['Mercedes-Benz', 'E-Class', '1993', '2024'],
            ['Mercedes-Benz', 'G-Class', '1979', '2024'],
            
            // BMW (Common in Kenya)
            ['BMW', '3 Series', '1975', '2024'],
            ['BMW', '5 Series', '1972', '2024'],
            ['BMW', 'X5', '1999', '2024'],
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
