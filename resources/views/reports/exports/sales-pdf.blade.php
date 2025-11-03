<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .totals { margin-top: 20px; }
        .summary { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total Sales: KES {{ number_format($totals['total_sales'], 2) }}</p>
        <p>Total Transactions: {{ number_format($totals['total_transactions']) }}</p>
        <p>Average Sale: KES {{ number_format($totals['avg_sale'], 2) }}</p>
        <p>Total Discount: KES {{ number_format($totals['total_discount'], 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->date->format('Y-m-d H:i') }}</td>
                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                <td>{{ $sale->saleItems->count() }}</td>
                <td>{{ number_format($sale->subtotal, 2) }}</td>
                <td>{{ number_format($sale->tax, 2) }}</td>
                <td>{{ number_format($sale->discount, 2) }}</td>
                <td>{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ $sale->payments->first() ? $sale->payments->first()->payment_method : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

