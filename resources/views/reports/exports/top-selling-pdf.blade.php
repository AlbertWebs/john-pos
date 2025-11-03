<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Top Selling Parts Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Top Selling Parts Report</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Part Number</th>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Quantity Sold</th>
                <th>Total Revenue</th>
                <th>Transactions</th>
                <th>Avg Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSelling as $index => $item)
            <tr>
                <td>#{{ $index + 1 }}</td>
                <td>{{ $item['part']->part_number }}</td>
                <td>{{ $item['part']->name }}</td>
                <td>{{ $item['part']->category ? $item['part']->category->name : 'N/A' }}</td>
                <td>{{ $item['part']->brand ? $item['part']->brand->brand_name : 'N/A' }}</td>
                <td>{{ number_format($item['total_quantity']) }}</td>
                <td>KES {{ number_format($item['total_revenue'], 2) }}</td>
                <td>{{ number_format($item['transaction_count']) }}</td>
                <td>KES {{ number_format($item['total_quantity'] > 0 ? $item['total_revenue'] / $item['total_quantity'] : 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

