<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccountsReceivableService
{
    public const AGING_BUCKETS = [
        'current' => ['label' => 'Current (0–30 days)', 'min' => 0, 'max' => 30],
        '31_60' => ['label' => '31–60 days', 'min' => 31, 'max' => 60],
        '61_90' => ['label' => '61–90 days', 'min' => 61, 'max' => 90],
        'over_90' => ['label' => 'Over 90 days', 'min' => 91, 'max' => null],
    ];

    public function buildReport(Carbon $asOf, ?Carbon $collectionsFrom = null, ?Carbon $collectionsTo = null): array
    {
        $invoices = $this->getOutstandingInvoices($asOf);
        $aging = $this->buildAgingSummary($invoices);
        $debtors = $this->buildDebtorSummary($invoices);
        $collections = $this->getCollections(
            $collectionsFrom ?? $asOf->copy()->startOfMonth(),
            $collectionsTo ?? $asOf->copy()->endOfDay()
        );

        return [
            'asOf' => $asOf,
            'collectionsFrom' => $collectionsFrom ?? $asOf->copy()->startOfMonth(),
            'collectionsTo' => $collectionsTo ?? $asOf->copy()->endOfDay(),
            'summary' => [
                'total_outstanding' => round($invoices->sum('balance'), 2),
                'invoice_count' => $invoices->count(),
                'debtor_count' => $debtors->filter(fn ($d) => $d->balance > 0.01)->count(),
                'overdue_count' => $invoices->where('is_overdue', true)->count(),
                'overdue_amount' => round($invoices->where('is_overdue', true)->sum('balance'), 2),
                'credit_outstanding' => round($invoices->where('is_credit', true)->sum('balance'), 2),
                'collections_total' => round($collections->sum('amount'), 2),
                'collections_count' => $collections->count(),
            ],
            'aging' => $aging,
            'debtors' => $debtors,
            'invoices' => $invoices,
            'collections' => $collections,
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function getOutstandingInvoices(Carbon $asOf): Collection
    {
        return Sale::with(['customer', 'payments'])
            ->outstanding()
            ->orderBy('date')
            ->get()
            ->map(function (Sale $sale) use ($asOf) {
                $balance = $sale->balanceDue();
                if ($balance < 0.01) {
                    return null;
                }

                $invoiceDate = $sale->date;
                $days = (int) $invoiceDate->diffInDays($asOf, false);
                if ($days < 0) {
                    $days = 0;
                }

                $bucket = $this->resolveAgingBucket($days);
                $overdue = $sale->due_date && $sale->due_date->lt($asOf->copy()->startOfDay());

                return (object) [
                    'sale' => $sale,
                    'customer' => $sale->customer,
                    'customer_name' => $sale->customer?->name ?? 'Walk-in / No customer',
                    'invoice_number' => $sale->invoice_number,
                    'invoice_date' => $invoiceDate,
                    'due_date' => $sale->due_date,
                    'is_credit' => (bool) $sale->is_credit,
                    'total' => (float) $sale->total_amount,
                    'paid' => $sale->amountPaid(),
                    'balance' => $balance,
                    'days_outstanding' => $days,
                    'aging_bucket' => $bucket,
                    'aging_label' => self::AGING_BUCKETS[$bucket]['label'],
                    'is_overdue' => $overdue,
                    'payment_status' => $sale->payment_status,
                ];
            })
            ->filter()
            ->sortByDesc('balance')
            ->values();
    }

    protected function buildAgingSummary(Collection $invoices): Collection
    {
        $buckets = collect();

        foreach (self::AGING_BUCKETS as $key => $config) {
            $items = $invoices->where('aging_bucket', $key);
            $buckets->push((object) [
                'key' => $key,
                'label' => $config['label'],
                'invoice_count' => $items->count(),
                'amount' => round($items->sum('balance'), 2),
            ]);
        }

        return $buckets;
    }

    protected function buildDebtorSummary(Collection $invoices): Collection
    {
        return $invoices
            ->groupBy(fn ($row) => $row->sale->customer_id ?? 0)
            ->map(function (Collection $rows, $customerId) {
                $customer = $rows->first()->customer;
                $agingAmounts = [];
                foreach (self::AGING_BUCKETS as $key => $config) {
                    $agingAmounts[$key] = round($rows->where('aging_bucket', $key)->sum('balance'), 2);
                }

                return (object) [
                    'customer_id' => $customerId ?: null,
                    'customer' => $customer,
                    'customer_name' => $customer?->name ?? 'Walk-in / No customer',
                    'phone' => $customer?->phone,
                    'invoice_count' => $rows->count(),
                    'balance' => round($rows->sum('balance'), 2),
                    'oldest_days' => $rows->max('days_outstanding'),
                    'aging' => $agingAmounts,
                ];
            })
            ->sortByDesc('balance')
            ->values();
    }

    /**
     * Payments received in period against credit / outstanding sales.
     *
     * @return Collection<int, object>
     */
    public function getCollections(Carbon $from, Carbon $to): Collection
    {
        return Payment::with(['sale.customer'])
            ->whereBetween('payment_date', [$from, $to])
            ->whereHas('sale', fn ($q) => $q->where('is_credit', true))
            ->orderByDesc('payment_date')
            ->get()
            ->map(function (Payment $payment) {
                return (object) [
                    'payment' => $payment,
                    'payment_date' => $payment->payment_date,
                    'payment_method' => $payment->payment_method,
                    'amount' => (float) $payment->amount,
                    'reference' => $payment->transaction_reference,
                    'sale' => $payment->sale,
                    'invoice_number' => $payment->sale?->invoice_number,
                    'customer_name' => $payment->sale?->customer?->name ?? '—',
                    'is_credit' => (bool) ($payment->sale?->is_credit ?? false),
                ];
            });
    }

    protected function resolveAgingBucket(int $days): string
    {
        foreach (self::AGING_BUCKETS as $key => $config) {
            if ($config['max'] === null && $days >= $config['min']) {
                return $key;
            }
            if ($days >= $config['min'] && $days <= $config['max']) {
                return $key;
            }
        }

        return 'over_90';
    }
}
