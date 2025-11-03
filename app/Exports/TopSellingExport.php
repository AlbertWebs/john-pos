<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TopSellingExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $topSelling;

    public function __construct($topSelling)
    {
        $this->topSelling = $topSelling;
    }

    public function collection()
    {
        return collect($this->topSelling);
    }

    public function headings(): array
    {
        return [
            'Part Number',
            'Name',
            'Category',
            'Brand',
            'Total Quantity Sold',
            'Total Revenue',
            'Transaction Count',
            'Average Price',
        ];
    }

    public function map($item): array
    {
        $part = $item['part'];
        $avgPrice = $item['total_quantity'] > 0 ? $item['total_revenue'] / $item['total_quantity'] : 0;

        return [
            $part->part_number,
            $part->name,
            $part->category ? $part->category->name : 'N/A',
            $part->brand ? $part->brand->brand_name : 'N/A',
            $item['total_quantity'],
            number_format($item['total_revenue'], 2),
            $item['transaction_count'],
            number_format($avgPrice, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

