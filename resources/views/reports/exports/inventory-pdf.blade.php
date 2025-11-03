<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Inventory Report</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <p>Total Items: {{ number_format($totals['total_items']) }}</p>
        <p>Total Value: KES {{ number_format($totals['total_value'], 2) }}</p>
        <p>Low Stock Items: {{ number_format($totals['low_stock_count']) }}</p>
        <p>Out of Stock: {{ number_format($totals['out_of_stock_count']) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Part Number</th>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th>Stock</th>
                <th>Reorder Level</th>
                <th>Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->part_number }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category ? $item->category->name : 'N/A' }}</td>
                <td>{{ $item->brand ? $item->brand->brand_name : 'N/A' }}</td>
                <td>{{ number_format($item->cost_price, 2) }}</td>
                <td>{{ number_format($item->selling_price, 2) }}</td>
                <td>{{ $item->stock_quantity }}</td>
                <td>{{ $item->reorder_level }}</td>
                <td>{{ number_format($item->stock_quantity * $item->cost_price, 2) }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

