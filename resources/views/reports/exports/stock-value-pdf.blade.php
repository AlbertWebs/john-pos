<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Value Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .num { text-align: right; }
        .header { text-align: center; margin-bottom: 16px; }
        .summary p { margin: 4px 0; }
        h2 { font-size: 13px; margin: 16px 0 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stock Value Report</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <p>Line items: {{ number_format($totals['total_items']) }}</p>
        <p>Units on hand: {{ number_format($totals['total_units']) }}</p>
        <p>Value at cost: KES {{ number_format($totals['cost_value'], 2) }}</p>
        <p>Value at retail: KES {{ number_format($totals['retail_value'], 2) }}</p>
        <p>Potential profit: KES {{ number_format($totals['potential_profit'], 2) }}</p>
    </div>

    @if($categoryBreakdown->count() > 0)
    <h2>By category</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th class="num">Items</th>
                <th class="num">Units</th>
                <th class="num">At cost</th>
                <th class="num">At retail</th>
                <th class="num">Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryBreakdown as $row)
            <tr>
                <td>{{ $row->category_name }}</td>
                <td class="num">{{ number_format($row->item_count) }}</td>
                <td class="num">{{ number_format($row->total_units) }}</td>
                <td class="num">{{ number_format($row->cost_value, 2) }}</td>
                <td class="num">{{ number_format($row->retail_value, 2) }}</td>
                <td class="num">{{ number_format($row->potential_profit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2>Items</h2>
    <table>
        <thead>
            <tr>
                <th>Part #</th>
                <th>Name</th>
                <th>Category</th>
                <th class="num">Qty</th>
                <th class="num">Cost</th>
                <th class="num">List</th>
                <th class="num">At cost</th>
                <th class="num">At retail</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->part_number }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category?->name ?? '—' }}</td>
                <td class="num">{{ $item->stock_quantity }}</td>
                <td class="num">{{ number_format($item->cost_price, 2) }}</td>
                <td class="num">{{ number_format($item->selling_price, 2) }}</td>
                <td class="num">{{ number_format($item->stock_quantity * $item->cost_price, 2) }}</td>
                <td class="num">{{ number_format($item->stock_quantity * $item->selling_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
