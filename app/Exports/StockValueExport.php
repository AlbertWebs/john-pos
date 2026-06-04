<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockValueExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected Collection $items,
        protected array $totals
    ) {}

    public function title(): string
    {
        return 'Stock value';
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Part number',
            'SKU',
            'Name',
            'Category',
            'Brand',
            'Qty on hand',
            'Cost price',
            'Selling price',
            'Value at cost',
            'Value at retail',
            'Potential profit',
            'Status',
        ];
    }

    public function map($item): array
    {
        $costValue = $item->stock_quantity * $item->cost_price;
        $retailValue = $item->stock_quantity * $item->selling_price;

        return [
            $item->part_number,
            $item->sku ?? '',
            $item->name,
            $item->category?->name ?? '',
            $item->brand?->brand_name ?? '',
            $item->stock_quantity,
            number_format($item->cost_price, 2, '.', ''),
            number_format($item->selling_price, 2, '.', ''),
            number_format($costValue, 2, '.', ''),
            number_format($retailValue, 2, '.', ''),
            number_format($retailValue - $costValue, 2, '.', ''),
            ucfirst($item->status),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
