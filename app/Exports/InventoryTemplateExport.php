<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'PN-1001',
                'SKU-1001',
                '1234567890123',
                'Sample Part Name',
                'Optional description of the part',
                'Existing Brand Name',
                'Category Name',
                'Vehicle Make Name',
                'Vehicle Model Name',
                '2015-2021',
                2500,
                2800,
                3200,
                15,
                5,
                'Aisle 3 - Shelf B',
                'active',
            ],
            [
                '# Leave blank to keep existing value when updating. Status must be active or inactive. Use numbers for pricing and stock columns.',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'part_number',
            'sku',
            'barcode',
            'name',
            'description',
            'brand',
            'category',
            'vehicle_make',
            'vehicle_model',
            'year_range',
            'cost_price',
            'min_price',
            'selling_price',
            'stock_quantity',
            'reorder_level',
            'location',
            'status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['color' => ['rgb' => '888888'], 'italic' => true]],
        ];
    }
}


