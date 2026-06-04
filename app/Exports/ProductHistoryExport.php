<?php

namespace App\Exports;

use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductHistoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected Inventory $product,
        protected Collection $events,
        protected ?Carbon $startDate,
        protected ?Carbon $endDate,
        protected array $summary
    ) {}

    public function title(): string
    {
        $name = preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', substr($this->product->name, 0, 20));

        return $name ?: 'Product history';
    }

    public function collection(): Collection
    {
        return $this->events;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Time',
            'Type',
            'Quantity',
            'Unit price',
            'Line total',
            'Reference',
            'Customer',
            'User',
            'Notes',
        ];
    }

    public function map($row): array
    {
        return [
            $row['date']->format('Y-m-d'),
            $row['date']->format('H:i'),
            $row['type_label'],
            $row['quantity_display'],
            $row['unit_price'] !== null ? number_format($row['unit_price'], 2, '.', '') : '',
            $row['line_total'] !== null ? number_format($row['line_total'], 2, '.', '') : '',
            $row['reference'],
            $row['customer'] ?? '',
            $row['user'] ?? '',
            $row['notes'] ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
