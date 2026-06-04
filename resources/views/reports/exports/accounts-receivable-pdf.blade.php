<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accounts Receivable</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 4px; }
        th { background: #f3f4f6; }
        .num { text-align: right; }
        h1 { font-size: 14px; }
        h2 { font-size: 11px; margin-top: 12px; }
    </style>
</head>
<body>
    <h1>Accounts Receivable Report</h1>
    <p>As of: {{ $asOf->format('Y-m-d') }} | Generated: {{ $date }}</p>
    <p>Outstanding: KES {{ number_format($summary['total_outstanding'], 2) }} |
       {{ $summary['invoice_count'] }} invoices |
       {{ $summary['debtor_count'] }} debtors |
       Collected {{ $collectionsFrom->format('Y-m-d') }}–{{ $collectionsTo->format('Y-m-d') }}: KES {{ number_format($summary['collections_total'], 2) }}</p>

    <h2>Aging</h2>
    <table>
        <tr><th>Bucket</th><th class="num">Invoices</th><th class="num">Amount</th></tr>
        @foreach($aging as $b)
        <tr><td>{{ $b->label }}</td><td class="num">{{ $b->invoice_count }}</td><td class="num">{{ number_format($b->amount, 2) }}</td></tr>
        @endforeach
    </table>

    <h2>Debtor summary</h2>
    <table>
        <tr><th>Customer</th><th class="num">Bal.</th><th class="num">0-30</th><th class="num">31-60</th><th class="num">61-90</th><th class="num">90+</th></tr>
        @foreach($debtors as $d)
        @if($d->balance > 0)
        <tr>
            <td>{{ $d->customer_name }}</td>
            <td class="num">{{ number_format($d->balance, 2) }}</td>
            <td class="num">{{ number_format($d->aging['current'] ?? 0, 0) }}</td>
            <td class="num">{{ number_format($d->aging['31_60'] ?? 0, 0) }}</td>
            <td class="num">{{ number_format($d->aging['61_90'] ?? 0, 0) }}</td>
            <td class="num">{{ number_format($d->aging['over_90'] ?? 0, 0) }}</td>
        </tr>
        @endif
        @endforeach
    </table>

    <h2>Outstanding invoices</h2>
    <table>
        <tr><th>Invoice</th><th>Customer</th><th>Date</th><th class="num">Balance</th><th>Aging</th></tr>
        @foreach($invoices as $inv)
        <tr>
            <td>{{ $inv->invoice_number }}</td>
            <td>{{ $inv->customer_name }}</td>
            <td>{{ $inv->invoice_date->format('Y-m-d') }}</td>
            <td class="num">{{ number_format($inv->balance, 2) }}</td>
            <td>{{ $inv->aging_label }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
