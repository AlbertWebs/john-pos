<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Status Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .low-stock {
            background-color: #fee2e2;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-low {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-ok {
            background-color: #dcfce7;
            color: #166534;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stock Status Report</h1>
        <p>{{ $settings['company_name'] ?? 'Auto Spares POS' }}</p>
        <p>{{ now()->format('F d, Y h:i A') }}</p>
    </div>
    
    <div class="content">
        @if($lowStockOnly)
            <h2>Low Stock Items Alert</h2>
            <p>The following items are running low on stock and may need to be reordered:</p>
        @else
            <h2>Complete Stock Status Report</h2>
            <p>Current inventory levels for all active items:</p>
        @endif

        @if($inventory->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory as $item)
                <tr class="{{ $item->isLowStock() ? 'low-stock' : '' }}">
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->sku ?? 'N/A' }}</td>
                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                    <td>{{ $item->stock_quantity }}</td>
                    <td>{{ $item->reorder_level }}</td>
                    <td>
                        @if($item->isLowStock())
                            <span class="status-badge status-low">Low Stock</span>
                        @else
                            <span class="status-badge status-ok">OK</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <h3>Summary</h3>
            <div class="summary-item">
                <strong>Total Items:</strong> {{ $inventory->count() }}
            </div>
            <div class="summary-item">
                <strong>Low Stock Items:</strong> <span style="color: #dc2626;">{{ $inventory->filter(fn($item) => $item->isLowStock())->count() }}</span>
            </div>
            <div class="summary-item">
                <strong>In Stock Items:</strong> <span style="color: #16a34a;">{{ $inventory->filter(fn($item) => !$item->isLowStock())->count() }}</span>
            </div>
        </div>
        @else
        <p>No items found matching the selected criteria.</p>
        @endif
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 5px; font-size: 12px; color: #6b7280;">
        <p>This is an automated email from {{ $settings['company_name'] ?? 'Auto Spares POS' }}.</p>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
