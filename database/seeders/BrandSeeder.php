<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
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
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['brand_name' => $brand['brand_name']],
                $brand
            );
        }
    }
}
