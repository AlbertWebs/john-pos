<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockAuditExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected Collection $rows,
        protected Carbon $periodFrom,
        protected Carbon $periodTo,
        protected object $totals
    ) {}

    public function title(): string
    {
        return $this->periodFrom->format('Y-m-d') . ' to ' . $this->periodTo->format('Y-m-d');
    }

    public function collection(): Collection
    {
        $out = $this->rows->values();

        $out->push((object) [
            'is_total' => true,
            'part' => null,
            'opening_stock' => $this->totals->opening_stock,
            'purchases' => $this->totals->purchases,
            'items_sold' => $this->totals->items_sold,
            'returns' => $this->totals->returns,
            'other_movements' => $this->totals->other_movements,
            'closing_stock' => $this->totals->closing_stock,
            'physical_stock' => $this->totals->physical_stock,
            'variance' => $this->totals->variance,
        ]);

        return $out;
    }

    public function headings(): array
    {
        return [
            'Part number',
            'SKU',
            'Name',
            'Category',
            'Opening stock',
            'Purchases',
            'Items sold',
            'Returns',
            'Other movements',
            'Closing (system)',
            'Physical stock',
            'Variance',
        ];
    }

    public function map($row): array
    {
        if (! empty($row->is_total)) {
            return [
                '',
                '',
                'TOTALS',
                '',
                $row->opening_stock,
                $row->purchases,
                $row->items_sold,
                $row->returns,
                $row->other_movements,
                $row->closing_stock,
                $row->physical_stock ?? '',
                $row->variance === null ? '' : $row->variance,
            ];
        }

        $p = $row->part;

        return [
            $p->part_number ?? '',
            $p->sku ?? '',
            $p->name,
            $p->category ? $p->category->name : '',
            $row->opening_stock,
            $row->purchases,
            $row->items_sold,
            $row->returns,
            $row->other_movements,
            $row->closing_stock,
            $row->physical_stock ?? '',
            $row->variance === null ? '' : $row->variance,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->rows->count() + 2;

        return [
            1 => ['font' => ['bold' => true]],
            $lastRow => ['font' => ['bold' => true]],
        ];
    }
}
