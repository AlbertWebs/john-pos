<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();
        $makes = VehicleMake::all();
        $models = VehicleModel::all();

        $parts = [
            ['name' => 'Engine Oil Filter', 'part_number' => 'OIL-FLT-001', 'cost' => 500, 'min' => 600, 'selling' => 800, 'stock' => 50, 'reorder' => 20],
            ['name' => 'Air Filter', 'part_number' => 'AIR-FLT-001', 'cost' => 300, 'min' => 400, 'selling' => 600, 'stock' => 75, 'reorder' => 30],
            ['name' => 'Brake Pad Set', 'part_number' => 'BRK-PAD-001', 'cost' => 2500, 'min' => 3000, 'selling' => 4000, 'stock' => 30, 'reorder' => 10],
            ['name' => 'Brake Disc', 'part_number' => 'BRK-DSC-001', 'cost' => 4500, 'min' => 5500, 'selling' => 7500, 'stock' => 25, 'reorder' => 8],
            ['name' => 'Spark Plug', 'part_number' => 'SPK-PLG-001', 'cost' => 800, 'min' => 1000, 'selling' => 1500, 'stock' => 100, 'reorder' => 40],
            ['name' => 'Timing Belt', 'part_number' => 'TIM-BLT-001', 'cost' => 3500, 'min' => 4500, 'selling' => 6000, 'stock' => 20, 'reorder' => 5],
            ['name' => 'Water Pump', 'part_number' => 'WAT-PMP-001', 'cost' => 5500, 'min' => 7000, 'selling' => 9500, 'stock' => 15, 'reorder' => 5],
            ['name' => 'Radiator', 'part_number' => 'RAD-001', 'cost' => 8500, 'min' => 11000, 'selling' => 15000, 'stock' => 12, 'reorder' => 4],
            ['name' => 'Alternator', 'part_number' => 'ALT-001', 'cost' => 12000, 'min' => 15000, 'selling' => 20000, 'stock' => 10, 'reorder' => 3],
            ['name' => 'Starter Motor', 'part_number' => 'STR-MTR-001', 'cost' => 10000, 'min' => 13000, 'selling' => 18000, 'stock' => 8, 'reorder' => 3],
            ['name' => 'Battery', 'part_number' => 'BAT-001', 'cost' => 8000, 'min' => 10000, 'selling' => 14000, 'stock' => 40, 'reorder' => 15],
            ['name' => 'Shock Absorber', 'part_number' => 'SHK-ABS-001', 'cost' => 4500, 'min' => 6000, 'selling' => 8500, 'stock' => 18, 'reorder' => 6],
            ['name' => 'Strut Assembly', 'part_number' => 'STR-ASM-001', 'cost' => 12000, 'min' => 15000, 'selling' => 20000, 'stock' => 12, 'reorder' => 4],
            ['name' => 'Fuel Filter', 'part_number' => 'FUL-FLT-001', 'cost' => 600, 'min' => 800, 'selling' => 1200, 'stock' => 60, 'reorder' => 25],
            ['name' => 'Fuel Pump', 'part_number' => 'FUL-PMP-001', 'cost' => 7500, 'min' => 9500, 'selling' => 13000, 'stock' => 14, 'reorder' => 5],
            ['name' => 'Oxygen Sensor', 'part_number' => 'O2-SNS-001', 'cost' => 3500, 'min' => 4500, 'selling' => 6500, 'stock' => 22, 'reorder' => 8],
            ['name' => 'Mass Air Flow Sensor', 'part_number' => 'MAF-SNS-001', 'cost' => 5500, 'min' => 7000, 'selling' => 9500, 'stock' => 16, 'reorder' => 6],
            ['name' => 'Throttle Body', 'part_number' => 'THR-BDY-001', 'cost' => 6500, 'min' => 8500, 'selling' => 12000, 'stock' => 10, 'reorder' => 4],
            ['name' => 'Ignition Coil', 'part_number' => 'IGN-CIL-001', 'cost' => 2500, 'min' => 3500, 'selling' => 5000, 'stock' => 28, 'reorder' => 10],
            ['name' => 'Distributor Cap', 'part_number' => 'DST-CAP-001', 'cost' => 1500, 'min' => 2000, 'selling' => 3000, 'stock' => 35, 'reorder' => 12],
            ['name' => 'Rotor', 'part_number' => 'ROT-001', 'cost' => 800, 'min' => 1000, 'selling' => 1500, 'stock' => 45, 'reorder' => 18],
            ['name' => 'Wiper Blade', 'part_number' => 'WIP-BLD-001', 'cost' => 600, 'min' => 800, 'selling' => 1200, 'stock' => 80, 'reorder' => 30],
            ['name' => 'Headlight Bulb', 'part_number' => 'HDL-BLB-001', 'cost' => 1200, 'min' => 1500, 'selling' => 2500, 'stock' => 50, 'reorder' => 20],
            ['name' => 'Tail Light Bulb', 'part_number' => 'TAL-BLB-001', 'cost' => 400, 'min' => 500, 'selling' => 800, 'stock' => 100, 'reorder' => 40],
            ['name' => 'Fog Light', 'part_number' => 'FOG-LGT-001', 'cost' => 3500, 'min' => 4500, 'selling' => 6500, 'stock' => 15, 'reorder' => 5],
            ['name' => 'Serpentine Belt', 'part_number' => 'SRP-BLT-001', 'cost' => 2000, 'min' => 2500, 'selling' => 4000, 'stock' => 32, 'reorder' => 12],
            ['name' => 'Power Steering Pump', 'part_number' => 'PSP-PMP-001', 'cost' => 8500, 'min' => 11000, 'selling' => 15000, 'stock' => 9, 'reorder' => 3],
            ['name' => 'Steering Rack', 'part_number' => 'STR-RCK-001', 'cost' => 15000, 'min' => 20000, 'selling' => 28000, 'stock' => 6, 'reorder' => 2],
            ['name' => 'Tie Rod End', 'part_number' => 'TIE-ROD-001', 'cost' => 2500, 'min' => 3500, 'selling' => 5000, 'stock' => 24, 'reorder' => 8],
            ['name' => 'Ball Joint', 'part_number' => 'BAL-JNT-001', 'cost' => 3000, 'min' => 4000, 'selling' => 6000, 'stock' => 20, 'reorder' => 7],
            ['name' => 'CV Joint', 'part_number' => 'CV-JNT-001', 'cost' => 4500, 'min' => 6000, 'selling' => 8500, 'stock' => 16, 'reorder' => 6],
            ['name' => 'Drive Shaft', 'part_number' => 'DRV-SFT-001', 'cost' => 12000, 'min' => 15000, 'selling' => 20000, 'stock' => 8, 'reorder' => 3],
            ['name' => 'Clutch Kit', 'part_number' => 'CLT-KIT-001', 'cost' => 8500, 'min' => 11000, 'selling' => 15000, 'stock' => 12, 'reorder' => 4],
            ['name' => 'Flywheel', 'part_number' => 'FLY-WHL-001', 'cost' => 15000, 'min' => 20000, 'selling' => 28000, 'stock' => 5, 'reorder' => 2],
            ['name' => 'Muffler', 'part_number' => 'MUF-001', 'cost' => 5500, 'min' => 7000, 'selling' => 9500, 'stock' => 14, 'reorder' => 5],
            ['name' => 'Catalytic Converter', 'part_number' => 'CAT-CNV-001', 'cost' => 25000, 'min' => 32000, 'selling' => 45000, 'stock' => 4, 'reorder' => 2],
            ['name' => 'Exhaust Pipe', 'part_number' => 'EXH-PIP-001', 'cost' => 3500, 'min' => 4500, 'selling' => 6500, 'stock' => 18, 'reorder' => 6],
            ['name' => 'Thermostat', 'part_number' => 'THM-001', 'cost' => 1200, 'min' => 1500, 'selling' => 2500, 'stock' => 42, 'reorder' => 15],
            ['name' => 'Coolant Hose', 'part_number' => 'CLN-HOS-001', 'cost' => 800, 'min' => 1000, 'selling' => 1500, 'stock' => 55, 'reorder' => 20],
            ['name' => 'Radiator Cap', 'part_number' => 'RAD-CAP-001', 'cost' => 400, 'min' => 500, 'selling' => 800, 'stock' => 70, 'reorder' => 25],
        ];

        foreach ($parts as $index => $part) {
            // Get the next available barcode number
            $barcodeNumber = $index + 1;
            $barcode = 'BC' . str_pad($barcodeNumber, 10, '0', STR_PAD_LEFT);
            
            // Ensure barcode is unique - find next available if exists
            while (Inventory::where('barcode', $barcode)->exists()) {
                $barcodeNumber++;
                $barcode = 'BC' . str_pad($barcodeNumber, 10, '0', STR_PAD_LEFT);
            }
            
            // Use updateOrCreate to avoid duplicates based on part_number
            Inventory::updateOrCreate(
                ['part_number' => $part['part_number']],
                [
                    'sku' => 'SKU-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    'barcode' => $barcode,
                    'name' => $part['name'],
                    'description' => 'High quality ' . $part['name'] . ' for various vehicle models',
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                    'vehicle_make_id' => $makes->random()->id,
                    'vehicle_model_id' => $models->random()->id ?? null,
                    'year_range' => '2010-2024',
                    'cost_price' => $part['cost'],
                    'min_price' => $part['min'],
                    'selling_price' => $part['selling'],
                    'stock_quantity' => $part['stock'],
                    'reorder_level' => $part['reorder'],
                    'location' => 'Shelf ' . chr(65 + ($index % 10)) . '-' . (($index % 20) + 1),
                    'status' => 'active',
                ]
            );
        }
    }
}
