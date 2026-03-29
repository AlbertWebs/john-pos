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

class SoldVsCostExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(protected Collection $items) {}

    public function title(): string
    {
        return 'Sold vs cost';
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
            'Cost price',
            'List selling price',
            'Qty sold (in range)',
            'Avg sold price',
            'Margin',
            'Margin %',
        ];
    }

    public function map($item): array
    {
        $cost = (float) $item->cost_price;
        $list = (float) $item->selling_price;
        $avgSold = $item->avg_sold_price !== null ? (float) $item->avg_sold_price : null;
        $margin = $avgSold !== null ? $avgSold - $cost : null;
        $marginPct = ($avgSold !== null && $cost > 0) ? (($avgSold - $cost) / $cost) * 100 : null;

        return [
            $item->part_number ?? '',
            $item->sku ?? '',
            $item->name,
            $item->category ? $item->category->name : '',
            round($cost, 2),
            round($list, 2),
            $item->qty_sold !== null ? (int) $item->qty_sold : '',
            $avgSold !== null ? round($avgSold, 2) : '',
            $margin !== null ? round($margin, 2) : '',
            $marginPct !== null ? round($marginPct, 2) . '%' : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
