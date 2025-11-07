<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class KenyaVehicleDataSeeder extends Seeder
{
    public function run(): void
    {
        $makes = [
            'Toyota',
            'Nissan',
            'Mazda',
            'Subaru',
            'Mitsubishi',
            'Suzuki',
            'Isuzu',
            'Honda',
            'Ford',
            'Mercedes-Benz',
            'BMW',
            'Volkswagen',
            'Peugeot',
            'Hyundai',
            'Kia',
            'Land Rover',
            'Audi',
            'Volvo',
            'Jeep',
            'Daihatsu',
            'Lexus',
            'Chevrolet',
            'Renault',
            'Tata',
            'Mahindra',
            'Hino',
            'Fuso',
            'Scania',
            'MAN',
            'UD Trucks',
            'Porsche',
        ];

        $makeIds = [];

        foreach ($makes as $makeName) {
            $make = VehicleMake::updateOrCreate(
                ['make_name' => $makeName],
                ['make_name' => $makeName]
            );

            $makeIds[$makeName] = $make->id;
        }

        $brands = [
            ['brand_name' => 'Bosch', 'country' => 'Germany'],
            ['brand_name' => 'Denso', 'country' => 'Japan'],
            ['brand_name' => 'NGK', 'country' => 'Japan'],
            ['brand_name' => 'Mann Filter', 'country' => 'Germany'],
            ['brand_name' => 'Mobil', 'country' => 'USA'],
            ['brand_name' => 'Castrol', 'country' => 'UK'],
            ['brand_name' => 'Delphi', 'country' => 'UK'],
            ['brand_name' => 'Valeo', 'country' => 'France'],
            ['brand_name' => 'Continental', 'country' => 'Germany'],
            ['brand_name' => 'TRW', 'country' => 'USA'],
            ['brand_name' => 'Brembo', 'country' => 'Italy'],
            ['brand_name' => 'Monroe', 'country' => 'USA'],
            ['brand_name' => 'KYB', 'country' => 'Japan'],
            ['brand_name' => 'Gates', 'country' => 'USA'],
            ['brand_name' => 'Mahle', 'country' => 'Germany'],
            ['brand_name' => 'Hella', 'country' => 'Germany'],
            ['brand_name' => 'Philips', 'country' => 'Netherlands'],
            ['brand_name' => 'Osram', 'country' => 'Germany'],
            ['brand_name' => 'ACDelco', 'country' => 'USA'],
            ['brand_name' => 'Mopar', 'country' => 'USA'],
            ['brand_name' => 'Motorcraft', 'country' => 'USA'],
            ['brand_name' => 'Beck Arnley', 'country' => 'USA'],
            ['brand_name' => 'Wix', 'country' => 'USA'],
            ['brand_name' => 'Fram', 'country' => 'USA'],
            ['brand_name' => 'K&N', 'country' => 'USA'],
            ['brand_name' => 'Meyle', 'country' => 'Germany'],
            ['brand_name' => 'Febi', 'country' => 'Germany'],
            ['brand_name' => 'Lemforder', 'country' => 'Germany'],
            ['brand_name' => 'Magneti Marelli', 'country' => 'Italy'],
            ['brand_name' => 'Pierburg', 'country' => 'Germany'],
            ['brand_name' => 'Sachs', 'country' => 'Germany'],
            ['brand_name' => 'Luk', 'country' => 'Germany'],
            ['brand_name' => 'ZF', 'country' => 'Germany'],
            ['brand_name' => 'SKF', 'country' => 'Sweden'],
            ['brand_name' => 'Timken', 'country' => 'USA'],
            ['brand_name' => 'Asimco', 'country' => 'Japan'],
            ['brand_name' => 'NZE', 'country' => 'Japan'],
            ['brand_name' => 'AllParts', 'country' => 'Japan'],
            ['brand_name' => 'RBI', 'country' => null],
            ['brand_name' => 'Jinbo', 'country' => null],
            ['brand_name' => 'URW', 'country' => null],

            // Additional common aftermarket brands in Kenya
            ['brand_name' => 'Exedy', 'country' => 'Japan'],
            ['brand_name' => 'Bilstein', 'country' => 'Germany'],
            ['brand_name' => 'Aisin', 'country' => 'Japan'],
            ['brand_name' => 'Ferodo', 'country' => 'United Kingdom'],
            ['brand_name' => 'Sumitomo', 'country' => 'Japan'],
            ['brand_name' => 'Toyo', 'country' => 'Japan'],
            ['brand_name' => 'Bridgestone', 'country' => 'Japan'],
            ['brand_name' => 'Pirelli', 'country' => 'Italy'],
            ['brand_name' => 'Goodyear', 'country' => 'USA'],
            ['brand_name' => 'Michelin', 'country' => 'France'],
            ['brand_name' => 'Nissin', 'country' => 'Japan'],
            ['brand_name' => 'Champion', 'country' => 'USA'],
            ['brand_name' => 'Koyo', 'country' => 'Japan'],
            ['brand_name' => 'Dayco', 'country' => 'USA'],
        ];

        foreach ($brands as $brandData) {
            $existing = Brand::whereRaw('LOWER(brand_name) = ?', [strtolower($brandData['brand_name'])])->first();

            if ($existing) {
                $existing->update($brandData);
            } else {
                Brand::create($brandData);
            }
        }

        $modelData = [
            'Toyota' => [
                ['model_name' => 'Hilux', 'year_start' => 1968, 'year_end' => null],
                ['model_name' => 'Land Cruiser', 'year_start' => 1951, 'year_end' => null],
                ['model_name' => 'Land Cruiser Prado', 'year_start' => 1984, 'year_end' => null],
                ['model_name' => 'Corolla', 'year_start' => 1966, 'year_end' => null],
                ['model_name' => 'Axio', 'year_start' => 2006, 'year_end' => null],
                ['model_name' => 'Fielder', 'year_start' => 2000, 'year_end' => null],
                ['model_name' => 'Probox', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'Succeed', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'Hiace', 'year_start' => 1967, 'year_end' => null],
                ['model_name' => 'Noah', 'year_start' => 2001, 'year_end' => null],
                ['model_name' => 'Voxy', 'year_start' => 2001, 'year_end' => null],
                ['model_name' => 'Wish', 'year_start' => 2003, 'year_end' => 2017],
                ['model_name' => 'Vitz', 'year_start' => 1999, 'year_end' => 2020],
                ['model_name' => 'Passo', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'RAV4', 'year_start' => 1994, 'year_end' => null],
                ['model_name' => 'Fortuner', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'Harrier', 'year_start' => 1997, 'year_end' => null],
                ['model_name' => 'Aurion', 'year_start' => 2006, 'year_end' => 2012],
                ['model_name' => 'Mark X', 'year_start' => 2004, 'year_end' => 2019],
                ['model_name' => 'Crown', 'year_start' => 1955, 'year_end' => null],
            ],
            'Nissan' => [
                ['model_name' => 'X-Trail', 'year_start' => 2000, 'year_end' => null],
                ['model_name' => 'Patrol', 'year_start' => 1951, 'year_end' => null],
                ['model_name' => 'Navara', 'year_start' => 1997, 'year_end' => null],
                ['model_name' => 'Note', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'Juke', 'year_start' => 2010, 'year_end' => null],
                ['model_name' => 'Qashqai', 'year_start' => 2006, 'year_end' => null],
                ['model_name' => 'March', 'year_start' => 1982, 'year_end' => null],
                ['model_name' => 'Serena', 'year_start' => 1991, 'year_end' => null],
                ['model_name' => 'Caravan', 'year_start' => 1973, 'year_end' => null],
                ['model_name' => 'Sunny', 'year_start' => 1966, 'year_end' => null],
                ['model_name' => 'Tiida', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'Dualis', 'year_start' => 2006, 'year_end' => 2013],
                ['model_name' => 'Murano', 'year_start' => 2002, 'year_end' => null],
            ],
            'Mazda' => [
                ['model_name' => 'Demio', 'year_start' => 1996, 'year_end' => null],
                ['model_name' => 'Axela', 'year_start' => 2003, 'year_end' => null],
                ['model_name' => 'Atenza', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'CX-5', 'year_start' => 2012, 'year_end' => null],
                ['model_name' => 'CX-3', 'year_start' => 2015, 'year_end' => null],
                ['model_name' => 'BT-50', 'year_start' => 2006, 'year_end' => null],
                ['model_name' => 'Premacy', 'year_start' => 1999, 'year_end' => 2018],
            ],
            'Subaru' => [
                ['model_name' => 'Forester', 'year_start' => 1997, 'year_end' => null],
                ['model_name' => 'Outback', 'year_start' => 1994, 'year_end' => null],
                ['model_name' => 'Impreza', 'year_start' => 1992, 'year_end' => null],
                ['model_name' => 'Legacy', 'year_start' => 1989, 'year_end' => null],
                ['model_name' => 'XV', 'year_start' => 2011, 'year_end' => null],
                ['model_name' => 'Levorg', 'year_start' => 2014, 'year_end' => null],
            ],
            'Mitsubishi' => [
                ['model_name' => 'Pajero', 'year_start' => 1982, 'year_end' => 2021],
                ['model_name' => 'Outlander', 'year_start' => 2001, 'year_end' => null],
                ['model_name' => 'L200', 'year_start' => 1978, 'year_end' => null],
                ['model_name' => 'ASX', 'year_start' => 2010, 'year_end' => null],
                ['model_name' => 'Lancer', 'year_start' => 1973, 'year_end' => 2017],
                ['model_name' => 'Canter', 'year_start' => 1963, 'year_end' => null],
            ],
            'Suzuki' => [
                ['model_name' => 'Swift', 'year_start' => 1983, 'year_end' => null],
                ['model_name' => 'Alto', 'year_start' => 1979, 'year_end' => null],
                ['model_name' => 'Vitara', 'year_start' => 1988, 'year_end' => null],
                ['model_name' => 'Wagon R', 'year_start' => 1993, 'year_end' => null],
                ['model_name' => 'Jimny', 'year_start' => 1970, 'year_end' => null],
                ['model_name' => 'Carry', 'year_start' => 1961, 'year_end' => null],
            ],
            'Isuzu' => [
                ['model_name' => 'D-Max', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'N-Series', 'year_start' => 1959, 'year_end' => null],
                ['model_name' => 'F-Series', 'year_start' => 1970, 'year_end' => null],
                ['model_name' => 'Elf', 'year_start' => 1959, 'year_end' => null],
                ['model_name' => 'Trooper', 'year_start' => 1981, 'year_end' => 2002],
            ],
            'Honda' => [
                ['model_name' => 'Fit', 'year_start' => 2001, 'year_end' => null],
                ['model_name' => 'CR-V', 'year_start' => 1995, 'year_end' => null],
                ['model_name' => 'Civic', 'year_start' => 1972, 'year_end' => null],
                ['model_name' => 'Accord', 'year_start' => 1976, 'year_end' => null],
                ['model_name' => 'Vezel', 'year_start' => 2013, 'year_end' => null],
                ['model_name' => 'HR-V', 'year_start' => 1998, 'year_end' => null],
            ],
            'Ford' => [
                ['model_name' => 'Ranger', 'year_start' => 1998, 'year_end' => null],
                ['model_name' => 'Everest', 'year_start' => 2003, 'year_end' => null],
                ['model_name' => 'Transit', 'year_start' => 1965, 'year_end' => null],
                ['model_name' => 'Focus', 'year_start' => 1998, 'year_end' => null],
                ['model_name' => 'Explorer', 'year_start' => 1990, 'year_end' => null],
            ],
            'Mercedes-Benz' => [
                ['model_name' => 'C-Class', 'year_start' => 1993, 'year_end' => null],
                ['model_name' => 'E-Class', 'year_start' => 1993, 'year_end' => null],
                ['model_name' => 'S-Class', 'year_start' => 1972, 'year_end' => null],
                ['model_name' => 'Sprinter', 'year_start' => 1995, 'year_end' => null],
                ['model_name' => 'Actros', 'year_start' => 1996, 'year_end' => null],
                ['model_name' => 'G-Class', 'year_start' => 1979, 'year_end' => null],
            ],
            'BMW' => [
                ['model_name' => '3 Series', 'year_start' => 1975, 'year_end' => null],
                ['model_name' => '5 Series', 'year_start' => 1972, 'year_end' => null],
                ['model_name' => 'X3', 'year_start' => 2003, 'year_end' => null],
                ['model_name' => 'X5', 'year_start' => 1999, 'year_end' => null],
                ['model_name' => 'X1', 'year_start' => 2009, 'year_end' => null],
            ],
            'Volkswagen' => [
                ['model_name' => 'Golf', 'year_start' => 1974, 'year_end' => null],
                ['model_name' => 'Passat', 'year_start' => 1973, 'year_end' => null],
                ['model_name' => 'Tiguan', 'year_start' => 2007, 'year_end' => null],
                ['model_name' => 'Touareg', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'Amarok', 'year_start' => 2010, 'year_end' => null],
                ['model_name' => 'Polo', 'year_start' => 1975, 'year_end' => null],
            ],
            'Peugeot' => [
                ['model_name' => '208', 'year_start' => 2012, 'year_end' => null],
                ['model_name' => '308', 'year_start' => 2007, 'year_end' => null],
                ['model_name' => '508', 'year_start' => 2010, 'year_end' => null],
                ['model_name' => '2008', 'year_start' => 2013, 'year_end' => null],
                ['model_name' => 'Partner', 'year_start' => 1996, 'year_end' => null],
            ],
            'Hyundai' => [
                ['model_name' => 'Tucson', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'Santa Fe', 'year_start' => 2000, 'year_end' => null],
                ['model_name' => 'Elantra', 'year_start' => 1990, 'year_end' => null],
                ['model_name' => 'Sonata', 'year_start' => 1985, 'year_end' => null],
                ['model_name' => 'Creta', 'year_start' => 2014, 'year_end' => null],
                ['model_name' => 'H100', 'year_start' => 1969, 'year_end' => null],
            ],
            'Kia' => [
                ['model_name' => 'Sportage', 'year_start' => 1993, 'year_end' => null],
                ['model_name' => 'Sorento', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'Rio', 'year_start' => 2000, 'year_end' => null],
                ['model_name' => 'Cerato', 'year_start' => 2003, 'year_end' => null],
                ['model_name' => 'Picanto', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'K2700', 'year_start' => 1997, 'year_end' => null],
            ],
            'Land Rover' => [
                ['model_name' => 'Defender', 'year_start' => 1983, 'year_end' => null],
                ['model_name' => 'Discovery', 'year_start' => 1989, 'year_end' => null],
                ['model_name' => 'Range Rover', 'year_start' => 1970, 'year_end' => null],
                ['model_name' => 'Range Rover Sport', 'year_start' => 2005, 'year_end' => null],
                ['model_name' => 'Freelander', 'year_start' => 1997, 'year_end' => 2014],
                ['model_name' => 'Range Rover Evoque', 'year_start' => 2011, 'year_end' => null],
            ],
            'Audi' => [
                ['model_name' => 'A4', 'year_start' => 1994, 'year_end' => null],
                ['model_name' => 'A6', 'year_start' => 1994, 'year_end' => null],
                ['model_name' => 'Q5', 'year_start' => 2008, 'year_end' => null],
                ['model_name' => 'Q7', 'year_start' => 2005, 'year_end' => null],
                ['model_name' => 'Q3', 'year_start' => 2011, 'year_end' => null],
            ],
            'Volvo' => [
                ['model_name' => 'XC60', 'year_start' => 2008, 'year_end' => null],
                ['model_name' => 'XC90', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'S60', 'year_start' => 2000, 'year_end' => null],
                ['model_name' => 'FH', 'year_start' => 1993, 'year_end' => null],
                ['model_name' => 'FM', 'year_start' => 1998, 'year_end' => null],
            ],
            'Jeep' => [
                ['model_name' => 'Wrangler', 'year_start' => 1986, 'year_end' => null],
                ['model_name' => 'Grand Cherokee', 'year_start' => 1992, 'year_end' => null],
                ['model_name' => 'Cherokee', 'year_start' => 1974, 'year_end' => null],
                ['model_name' => 'Compass', 'year_start' => 2006, 'year_end' => null],
            ],
            'Daihatsu' => [
                ['model_name' => 'Mira', 'year_start' => 1980, 'year_end' => null],
                ['model_name' => 'Terios', 'year_start' => 1997, 'year_end' => null],
                ['model_name' => 'Sirion', 'year_start' => 1998, 'year_end' => null],
                ['model_name' => 'Hijet', 'year_start' => 1960, 'year_end' => null],
            ],
            'Lexus' => [
                ['model_name' => 'RX', 'year_start' => 1998, 'year_end' => null],
                ['model_name' => 'NX', 'year_start' => 2014, 'year_end' => null],
                ['model_name' => 'LX', 'year_start' => 1995, 'year_end' => null],
                ['model_name' => 'GX', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'ES', 'year_start' => 1989, 'year_end' => null],
            ],
            'Chevrolet' => [
                ['model_name' => 'Trailblazer', 'year_start' => 2001, 'year_end' => null],
                ['model_name' => 'Colorado', 'year_start' => 2003, 'year_end' => null],
                ['model_name' => 'Captiva', 'year_start' => 2006, 'year_end' => null],
                ['model_name' => 'Cruze', 'year_start' => 2008, 'year_end' => 2019],
            ],
            'Renault' => [
                ['model_name' => 'Duster', 'year_start' => 2010, 'year_end' => null],
                ['model_name' => 'Koleos', 'year_start' => 2006, 'year_end' => null],
                ['model_name' => 'Kwid', 'year_start' => 2015, 'year_end' => null],
                ['model_name' => 'Kangoo', 'year_start' => 1997, 'year_end' => null],
            ],
            'Tata' => [
                ['model_name' => 'Xenon', 'year_start' => 2008, 'year_end' => null],
                ['model_name' => '407', 'year_start' => 1986, 'year_end' => null],
                ['model_name' => '713', 'year_start' => 1996, 'year_end' => null],
                ['model_name' => 'LPT', 'year_start' => 1986, 'year_end' => null],
            ],
            'Mahindra' => [
                ['model_name' => 'Scorpio', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'Bolero', 'year_start' => 2000, 'year_end' => null],
                ['model_name' => 'XUV500', 'year_start' => 2011, 'year_end' => null],
                ['model_name' => 'Pickup', 'year_start' => 2005, 'year_end' => null],
            ],
            'Hino' => [
                ['model_name' => '300 Series', 'year_start' => 2001, 'year_end' => null],
                ['model_name' => '500 Series', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => '700 Series', 'year_start' => 2004, 'year_end' => null],
            ],
            'Fuso' => [
                ['model_name' => 'Canter', 'year_start' => 1963, 'year_end' => null],
                ['model_name' => 'Fighter', 'year_start' => 1984, 'year_end' => null],
                ['model_name' => 'Rosa', 'year_start' => 1960, 'year_end' => null],
            ],
            'Scania' => [
                ['model_name' => 'P-Series', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'G-Series', 'year_start' => 2007, 'year_end' => null],
                ['model_name' => 'R-Series', 'year_start' => 2004, 'year_end' => null],
            ],
            'MAN' => [
                ['model_name' => 'TGS', 'year_start' => 2007, 'year_end' => null],
                ['model_name' => 'TGM', 'year_start' => 2005, 'year_end' => null],
                ['model_name' => 'TGA', 'year_start' => 2000, 'year_end' => 2007],
            ],
            'UD Trucks' => [
                ['model_name' => 'Quon', 'year_start' => 2004, 'year_end' => null],
                ['model_name' => 'Condor', 'year_start' => 1993, 'year_end' => null],
                ['model_name' => 'Quest', 'year_start' => 2011, 'year_end' => null],
            ],
            'Porsche' => [
                ['model_name' => 'Cayenne', 'year_start' => 2002, 'year_end' => null],
                ['model_name' => 'Macan', 'year_start' => 2014, 'year_end' => null],
                ['model_name' => 'Panamera', 'year_start' => 2009, 'year_end' => null],
            ],
        ];

        foreach ($modelData as $makeName => $models) {
            if (!isset($makeIds[$makeName])) {
                continue;
            }

            foreach ($models as $model) {
                VehicleModel::updateOrCreate(
                    [
                        'vehicle_make_id' => $makeIds[$makeName],
                        'model_name' => $model['model_name'],
                    ],
                    [
                        'year_start' => $model['year_start'],
                        'year_end' => $model['year_end'],
                    ]
                );
            }
        }
    }
}



