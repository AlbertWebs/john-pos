<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $items;
    protected $totals;

    public function __construct($items, $totals)
    {
        $this->items = $items;
        $this->totals = $totals;
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Part Number',
            'SKU',
            'Name',
            'Category',
            'Brand',
            'Cost Price',
            'Selling Price',
            'Stock Quantity',
            'Reorder Level',
            'Status',
            'Total Value',
        ];
    }

    public function map($item): array
    {
        return [
            $item->part_number,
            $item->sku ?? 'N/A',
            $item->name,
            $item->category ? $item->category->name : 'N/A',
            $item->brand ? $item->brand->brand_name : 'N/A',
            number_format($item->cost_price, 2),
            number_format($item->selling_price, 2),
            $item->stock_quantity,
            $item->reorder_level,
            ucfirst($item->status),
            number_format($item->stock_quantity * $item->cost_price, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

