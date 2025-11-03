<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $sales;
    protected $totals;
    protected $paymentBreakdown;

    public function __construct($sales, $totals, $paymentBreakdown)
    {
        $this->sales = $sales;
        $this->totals = $totals;
        $this->paymentBreakdown = $paymentBreakdown;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Date',
            'Customer',
            'Items Count',
            'Subtotal',
            'Tax',
            'Discount',
            'Total Amount',
            'Payment Method',
            'Cashier',
        ];
    }

    public function map($sale): array
    {
        $paymentMethod = $sale->payments->first() ? $sale->payments->first()->payment_method : 'N/A';
        
        return [
            $sale->invoice_number,
            $sale->date->format('Y-m-d H:i:s'),
            $sale->customer ? $sale->customer->name : 'Walk-in',
            $sale->saleItems->count(),
            number_format($sale->subtotal, 2),
            number_format($sale->tax, 2),
            number_format($sale->discount, 2),
            number_format($sale->total_amount, 2),
            $paymentMethod,
            $sale->user->name,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

