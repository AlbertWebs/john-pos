<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .details-row {
            display: table-row;
        }
        .details-cell {
            display: table-cell;
            padding: 8px 0;
            width: 50%;
        }
        .details-cell.right {
            text-align: right;
        }
        .label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        .value {
            font-size: 14px;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        thead {
            background-color: #f5f5f5;
        }
        th {
            text-align: left;
            padding: 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #333;
        }
        th.right {
            text-align: right;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        td.right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .total-row.final {
            border-bottom: 2px solid #333;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        .payment-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            @if(isset($settings['logo']) && $settings['logo'])
            <div style="margin-bottom: 15px;">
                <img src="{{ public_path('storage/' . $settings['logo']) }}" alt="Logo" style="max-height: 60px;">
            </div>
            @endif
            <h1>{{ $settings['company_name'] ?? config('app.name', 'Spare Parts POS') }}</h1>
            <p>Sale Receipt</p>
            @if(isset($settings['address']) || isset($settings['phone']) || isset($settings['email']))
            <div style="margin-top: 10px; font-size: 10px; color: #666;">
                @if(isset($settings['address']))<p>{{ $settings['address'] }}</p>@endif
                @if(isset($settings['phone']))<p>Phone: {{ $settings['phone'] }}</p>@endif
                @if(isset($settings['email']))<p>Email: {{ $settings['email'] }}</p>@endif
            </div>
            @endif
        </div>

        <!-- Invoice Details -->
        <div class="details">
            <div class="details-row">
                <div class="details-cell">
                    <div class="label">Invoice Number</div>
                    <div class="value">{{ $sale->invoice_number }}</div>
                </div>
                <div class="details-cell right">
                    <div class="label">Date</div>
                    <div class="value">{{ $sale->date->format('M d, Y h:i A') }}</div>
                </div>
            </div>
            @if($sale->customer)
            <div class="details-row">
                <div class="details-cell">
                    <div class="label">Customer</div>
                    <div class="value">{{ $sale->customer->name }}</div>
                </div>
                <div class="details-cell right">
                    <div class="label">Cashier</div>
                    <div class="value">{{ $sale->user->name }}</div>
                </div>
            </div>
            @else
            <div class="details-row">
                <div class="details-cell right">
                    <div class="label">Cashier</div>
                    <div class="value">{{ $sale->user->name }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Qty</th>
                    <th class="right">Price</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->part->name }}</strong><br>
                        <small>{{ $item->part->part_number }}</small>
                    </td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">KES {{ number_format($item->price, 2) }}</td>
                    <td class="right">KES {{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>KES {{ number_format($sale->subtotal, 2) }}</span>
            </div>
            @if($sale->tax > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>KES {{ number_format($sale->tax, 2) }}</span>
            </div>
            @endif
            @if($sale->discount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>- KES {{ number_format($sale->discount, 2) }}</span>
            </div>
            @endif
            <div class="total-row final">
                <span>Total Amount:</span>
                <span>KES {{ number_format($sale->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Payment Information -->
        @if($sale->payments->count() > 0)
        <div class="payment-info">
            <div class="label" style="margin-bottom: 10px;">Payment Details:</div>
            @foreach($sale->payments as $payment)
            <div class="total-row">
                <span>{{ $payment->payment_method }}:</span>
                <span>KES {{ number_format($payment->amount, 2) }}</span>
            </div>
            @if($payment->transaction_reference)
            <div style="font-size: 10px; color: #666; margin-top: 5px;">
                Reference: {{ $payment->transaction_reference }}
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            @if($sale->customer)
            <p style="margin-top: 5px;">Loyalty Points: {{ number_format($sale->customer->loyalty_points) }}</p>
            @endif
        </div>
    </div>
</body>
</html>

