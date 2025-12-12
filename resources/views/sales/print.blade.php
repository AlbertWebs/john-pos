<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt - {{ $sale->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            line-height: 1.2;
            color: #000000 !important;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .print-container {
            max-width: 72mm;
            width: 72mm;
            margin: 20px auto;
            padding: 3mm;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .print-actions {
            max-width: 72mm;
            width: 72mm;
            margin: 20px auto;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        /* Receipt Styles */
        .receipt {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 4px;
            border-bottom: 2px solid #000000;
            padding-bottom: 4px;
        }
        
        .header h1 {
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 2px;
            line-height: 1.1;
            color: #000000 !important;
        }
        
        .header p {
            font-size: 9px;
            margin: 1px 0;
            font-weight: 600;
            color: #000000 !important;
        }
        
        .header img {
            max-height: 30px;
            max-width: 100%;
            margin-bottom: 4px;
        }
        
        .details {
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 1px solid #000000;
            font-size: 8px;
        }
        
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .label {
            font-weight: 800;
            font-size: 7px;
            text-transform: uppercase;
            color: #000000 !important;
        }
        
        .value {
            font-size: 8px;
            font-weight: 600;
            color: #000000 !important;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 8px;
        }
        
        th {
            text-align: left;
            padding: 1px;
            font-size: 7px;
            font-weight: 900;
            text-transform: uppercase;
            border-bottom: 2px solid #000000;
            color: #000000 !important;
        }
        
        th.right {
            text-align: right;
        }
        
        td {
            padding: 1px;
            border-bottom: 1px solid #000000;
            font-size: 8px;
            line-height: 1.1;
            font-weight: 600;
            color: #000000 !important;
        }
        
        td.right {
            text-align: right;
        }
        
        .item-name {
            font-size: 8px;
            margin-bottom: 1px;
            font-weight: 700;
            color: #000000 !important;
        }
        
        .item-part {
            font-size: 7px;
            color: #000000 !important;
            font-weight: 500;
        }
        
        .totals {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #000000;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 8px;
            font-weight: 700;
            color: #000000 !important;
        }
        
        .total-row.final {
            border-top: 2px solid #000000;
            font-weight: 900;
            font-size: 10px;
            margin-top: 2px;
            padding-top: 2px;
            color: #000000 !important;
        }
        
        .payment-info {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #000000;
        }
        
        .payment-info .label {
            font-weight: 800;
            color: #000000 !important;
        }
        
        .payment-info .total-row {
            font-weight: 700;
            color: #000000 !important;
        }
        
        .footer {
            margin-top: 4px;
            text-align: center;
            padding-top: 4px;
            border-top: 1px solid #000000;
            font-size: 8px;
            font-weight: 600;
            color: #000000 !important;
        }
        
        .footer p {
            font-weight: 600;
            color: #000000 !important;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .print-actions {
                display: none !important;
            }
            
            .print-container {
                margin: 0;
                padding: 3mm;
                box-shadow: none;
                max-width: 72mm;
                width: 72mm;
            }
            
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button onclick="window.print()" class="btn">🖨️ Print Receipt</button>
        <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">← Back to Receipt</a>
    </div>
    
    <div class="print-container">
        <div class="receipt">
            <!-- Header -->
            <div class="header">
                @if(isset($settings['logo']) && $settings['logo'])
                <div>
                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo">
                </div>
                @endif
                <h1>{{ $settings['company_name'] ?? config('app.name', 'Spare Parts POS') }}</h1>
                <p>Sale Receipt</p>
                @if(isset($settings['address']) || isset($settings['phone']) || isset($settings['kra_pin']))
                <div style="margin-top: 2px; font-size: 8px;">
                    @if(isset($settings['address']))<p style="margin: 1px 0;">{{ strlen($settings['address']) > 35 ? substr($settings['address'], 0, 32) . '...' : $settings['address'] }}</p>@endif
                    @if(isset($settings['phone']))<p style="margin: 1px 0;">Tel: {{ $settings['phone'] }}</p>@endif
                    @if(isset($settings['kra_pin']))<p style="margin: 1px 0; font-weight: bold;">KRA PIN: {{ $settings['kra_pin'] }}</p>@endif
                </div>
                @endif
            </div>

            <!-- Invoice Details -->
            <div class="details">
                <div class="details-row">
                    <div>
                        <span class="label">Invoice:</span>
                        <span class="value">{{ $sale->invoice_number }}</span>
                    </div>
                    <div>
                        <span class="label">Date:</span>
                        <span class="value">{{ $sale->date->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                @if($sale->customer)
                <div class="details-row">
                    <div>
                        <span class="label">Customer:</span>
                        <span class="value">{{ $sale->customer->name }}</span>
                    </div>
                    <div>
                        <span class="label">Cashier:</span>
                        <span class="value">{{ $sale->user->name }}</span>
                    </div>
                </div>
                @else
                <div class="details-row">
                    <div>
                        <span class="label">Cashier:</span>
                        <span class="value">{{ $sale->user->name }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Items Table -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 28mm;">Item</th>
                        <th class="right" style="width: 8mm;">Qty</th>
                        <th class="right" style="width: 18mm;">Price</th>
                        <th class="right" style="width: 18mm;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleItems as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ strlen($item->part->name) > 20 ? substr($item->part->name, 0, 17) . '...' : $item->part->name }}</div>
                            <div class="item-part">{{ $item->part->part_number }}</div>
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
                    <span>VAT (16%):</span>
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
                <div class="label" style="margin-bottom: 4px;">Payment:</div>
                @foreach($sale->payments as $payment)
                <div class="total-row">
                    <span>{{ $payment->payment_method }}:</span>
                    <span>KES {{ number_format($payment->amount, 2) }}</span>
                </div>
                @if($payment->transaction_reference)
                <div style="font-size: 7px; margin-top: 1px; text-align: right;">
                    Ref: {{ strlen($payment->transaction_reference) > 20 ? substr($payment->transaction_reference, 0, 17) . '...' : $payment->transaction_reference }}
                </div>
                @endif
                @endforeach
            </div>
            @endif

            <!-- eTIMS Information -->
            @if($sale->generate_etims_receipt)
            <div class="payment-info">
                <div class="label" style="margin-bottom: 4px;">eTIMS Information:</div>
                @if($sale->etims_verified)
                    <div style="font-size: 7px; margin-bottom: 2px;">
                        <div style="font-weight: bold; margin-bottom: 2px;">✓ eTIMS Verified</div>
                        @if($sale->etims_invoice_number)
                        <div style="margin-bottom: 1px;">Invoice: {{ $sale->etims_invoice_number }}</div>
                        @endif
                        @if($sale->etims_uuid)
                        <div style="margin-bottom: 1px;">UUID: {{ strlen($sale->etims_uuid) > 20 ? substr($sale->etims_uuid, 0, 17) . '...' : $sale->etims_uuid }}</div>
                        @endif
                        @if($sale->etims_approval_date)
                        <div>Approved: {{ $sale->etims_approval_date->format('d/m/Y H:i') }}</div>
                        @endif
                    </div>
                @else
                    <div style="font-size: 7px;">
                        Status: Awaiting confirmation from KRA...
                    </div>
                @endif
            </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <p>Thank you for your business!</p>
                @if($sale->customer)
                <p style="margin-top: 2px;">Loyalty Points: {{ number_format($sale->customer->loyalty_points) }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        // Auto-print on page load when opened from POS
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>

