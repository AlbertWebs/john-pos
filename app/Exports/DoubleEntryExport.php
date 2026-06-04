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

class DoubleEntryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected Collection $rows,
        protected Carbon $startDate,
        protected Carbon $endDate,
        protected array $totals
    ) {}

    public function title(): string
    {
        return $this->startDate->format('Y-m-d') . ' to ' . $this->endDate->format('Y-m-d');
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Journal #',
            'Date',
            'Type',
            'Reference',
            'Account',
            'Debit',
            'Credit',
        ];
    }

    public function map($row): array
    {
        return [
            $row['journal_no'],
            $row['date']->format('Y-m-d H:i'),
            $row['type'],
            $row['reference'],
            $row['account'],
            $row['side'] === 'debit' ? number_format($row['amount'], 2, '.', '') : '',
            $row['side'] === 'credit' ? number_format($row['amount'], 2, '.', '') : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
