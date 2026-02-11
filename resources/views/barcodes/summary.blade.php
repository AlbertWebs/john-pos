<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Printing Summary</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 10mm;
            margin: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15mm;
            border-bottom: 3px solid #000;
            padding-bottom: 5mm;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5mm;
        }
        
        .header p {
            font-size: 14px;
            color: #666;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
        }
        
        .summary-table thead {
            background-color: #333;
            color: #fff;
        }
        
        .summary-table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #000;
        }
        
        .summary-table td {
            padding: 6px 8px;
            border: 1px solid #000;
        }
        
        .summary-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        
        .summary-table tbody tr:hover {
            background-color: #e8e8e8;
        }
        
        .print-count {
            font-weight: bold;
            font-size: 14px;
            color: #0066cc;
            text-align: center;
        }
        
        .quantity {
            text-align: center;
            font-weight: bold;
        }
        
        .barcode-col {
            font-family: 'Courier New', monospace;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 15mm;
            padding-top: 5mm;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .total-row {
            background-color: #ffffcc !important;
            font-weight: bold;
        }
        
        @page {
            size: A4;
            margin: 10mm;
        }
        
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Barcode Sticker Printing Instructions</h1>
        <p>Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
    
    <table class="summary-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Product Name</th>
                <th style="width: 15%;">Part Number</th>
                <th style="width: 15%;">Barcode</th>
                <th style="width: 10%;">Stock Qty</th>
                <th style="width: 15%;">Print Copies</th>
                <th style="width: 10%;">Page #</th>
            </tr>
        </thead>
        <tbody>
            @php
                $pageNumber = 1;
                $totalStickers = 0;
                $totalPages = 0;
            @endphp
            @foreach($items as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->part_number }}</td>
                <td class="barcode-col">{{ $item->barcode }}</td>
                <td class="quantity">{{ $item->stock_quantity }}</td>
                <td class="print-count">{{ $item->stock_quantity }}x</td>
                <td style="text-align: center;">{{ $pageNumber }}</td>
            </tr>
            @php
                $pageNumber++;
                $totalStickers += $item->stock_quantity;
                $totalPages++;
            @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right; padding-right: 10px;"><strong>TOTALS:</strong></td>
                <td class="quantity">{{ $totalStickers }}</td>
                <td class="print-count">{{ $totalStickers }}x</td>
                <td style="text-align: center;">{{ $totalPages }} pages</td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>Instructions:</strong> Print each page the number of times shown in the "Print Copies" column.</p>
        <p>For example, if a product shows "Print Copies: 5x", print that page 5 times to get 5 stickers for that product.</p>
    </div>
</body>
</html>
