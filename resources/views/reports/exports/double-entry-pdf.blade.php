<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Double Entry Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background: #f3f4f6; }
        .num { text-align: right; }
        h1 { font-size: 16px; }
        h2 { font-size: 12px; margin-top: 14px; }
    </style>
</head>
<body>
    <h1>Double Entry Report</h1>
    <p>Period: {{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</p>
    <p>Generated: {{ $date }}</p>
    <p>Total debits: KES {{ number_format($totals['total_debits'], 2) }} | Total credits: KES {{ number_format($totals['total_credits'], 2) }} | Balanced: {{ $totals['balanced'] ? 'Yes' : 'No' }}</p>

    <h2>Trial balance</h2>
    <table>
        <thead>
            <tr><th>Account</th><th class="num">Debits</th><th class="num">Credits</th><th class="num">Net</th></tr>
        </thead>
        <tbody>
            @foreach($trialBalance as $account)
            <tr>
                <td>{{ $account->account }}</td>
                <td class="num">{{ number_format($account->debits, 2) }}</td>
                <td class="num">{{ number_format($account->credits, 2) }}</td>
                <td class="num">{{ number_format($account->net, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Journal</h2>
    <table>
        <thead>
            <tr><th>#</th><th>Date</th><th>Type</th><th>Reference</th><th>Account</th><th class="num">Debit</th><th class="num">Credit</th></tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row['is_first_line'] ? $row['journal_no'] : '' }}</td>
                <td>{{ $row['is_first_line'] ? $row['date']->format('Y-m-d H:i') : '' }}</td>
                <td>{{ $row['is_first_line'] ? $row['type'] : '' }}</td>
                <td>{{ $row['is_first_line'] ? $row['reference'] : '' }}</td>
                <td>{{ $row['account'] }}</td>
                <td class="num">{{ $row['side'] === 'debit' ? number_format($row['amount'], 2) : '' }}</td>
                <td class="num">{{ $row['side'] === 'credit' ? number_format($row['amount'], 2) : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
