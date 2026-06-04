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

class AccountsReceivableExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected array $report
    ) {}

    public function title(): string
    {
        return 'Accounts Receivable';
    }

    public function collection(): Collection
    {
        $rows = collect();

        $rows->push(['SECTION', 'Accounts Receivable Summary']);
        $rows->push(['As of', $this->report['asOf']->format('Y-m-d')]);
        $rows->push(['Total outstanding', $this->report['summary']['total_outstanding']]);
        $rows->push(['Open invoices', $this->report['summary']['invoice_count']]);
        $rows->push(['Debtors with balance', $this->report['summary']['debtor_count']]);
        $rows->push([]);

        $rows->push(['SECTION', 'Aging']);
        foreach ($this->report['aging'] as $bucket) {
            $rows->push([$bucket->label, $bucket->amount, $bucket->invoice_count . ' invoices']);
        }
        $rows->push([]);

        $rows->push(['SECTION', 'Debtor summary']);
        $rows->push(['Customer', 'Phone', 'Invoices', 'Balance', '0-30', '31-60', '61-90', '90+', 'Oldest days']);
        foreach ($this->report['debtors'] as $d) {
            if ($d->balance < 0.01) {
                continue;
            }
            $rows->push([
                $d->customer_name,
                $d->phone ?? '',
                $d->invoice_count,
                $d->balance,
                $d->aging['current'] ?? 0,
                $d->aging['31_60'] ?? 0,
                $d->aging['61_90'] ?? 0,
                $d->aging['over_90'] ?? 0,
                $d->oldest_days,
            ]);
        }
        $rows->push([]);

        $rows->push(['SECTION', 'Outstanding invoices']);
        $rows->push(['Invoice', 'Customer', 'Date', 'Due', 'Total', 'Paid', 'Balance', 'Days', 'Aging', 'Credit']);
        foreach ($this->report['invoices'] as $inv) {
            $rows->push([
                $inv->invoice_number,
                $inv->customer_name,
                $inv->invoice_date->format('Y-m-d'),
                $inv->due_date?->format('Y-m-d') ?? '',
                $inv->total,
                $inv->paid,
                $inv->balance,
                $inv->days_outstanding,
                $inv->aging_label,
                $inv->is_credit ? 'Yes' : 'No',
            ]);
        }
        $rows->push([]);

        $rows->push(['SECTION', 'Collections ' . $this->report['collectionsFrom']->format('Y-m-d') . ' to ' . $this->report['collectionsTo']->format('Y-m-d')]);
        $rows->push(['Date', 'Invoice', 'Customer', 'Method', 'Amount', 'Reference']);
        foreach ($this->report['collections'] as $c) {
            $rows->push([
                $c->payment_date->format('Y-m-d H:i'),
                $c->invoice_number,
                $c->customer_name,
                $c->payment_method,
                $c->amount,
                $c->reference ?? '',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Field', 'Value / Detail', 'Extra'];
    }

    public function map($row): array
    {
        if (is_array($row)) {
            return array_pad($row, 3, '');
        }

        return [$row];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
