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
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .receipt {
            max-width: 72mm;
            width: 72mm;
            margin: 0 auto;
            padding: 3mm;
            background: #fff;
            font-family: 'Courier New', Courier, monospace;
        }
        .header {
            text-align: center;
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }
        .header h1 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
            line-height: 1.1;
        }
        .header p {
            font-size: 9px;
            color: #000;
            margin: 1px 0;
        }
        .details {
            display: table;
            width: 100%;
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 1px dashed #000;
        }
        .details-row {
            display: table-row;
        }
        .details-cell {
            display: table-cell;
            padding: 2px 0;
            width: 50%;
        }
        .details-cell.right {
            text-align: right;
        }
        .label {
            font-weight: bold;
            color: #000;
            font-size: 7px;
            text-transform: uppercase;
        }
        .value {
            font-size: 8px;
            margin-top: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            font-size: 8px;
        }
        thead {
            background-color: #fff;
        }
        th {
            text-align: left;
            padding: 1px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
        }
        th.right {
            text-align: right;
        }
        td {
            padding: 1px;
            border-bottom: 1px dotted #000;
            font-size: 8px;
            line-height: 1.1;
            vertical-align: top;
        }
        td.right {
            text-align: right;
            vertical-align: middle;
        }
        .totals {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px dashed #000;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px dotted #000;
            font-size: 8px;
        }
        .total-row.final {
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-size: 10px;
            margin-top: 2px;
            padding-top: 2px;
        }
        .payment-info {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px dashed #000;
        }
        .payment-info .label {
            margin-bottom: 4px;
        }
        .payment-info .total-row {
            font-size: 8px;
        }
        .footer {
            margin-top: 4px;
            text-align: center;
            padding-top: 4px;
            border-top: 1px dashed #000;
            font-size: 8px;
            color: #000;
        }
        .footer p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            @if(isset($settings['logo']) && $settings['logo'])
            <div style="margin-bottom: 4px;">
                <img src="{{ public_path('storage/' . $settings['logo']) }}" alt="Logo" style="max-height: 30px; max-width: 100%;">
            </div>
            @endif
            <h1>{{ $settings['company_name'] ?? config('app.name', 'Spare Parts POS') }}</h1>
            <p>Sale Receipt</p>
            <div style="margin-top: 2px; font-size: 8px; color: #000; text-align: center;">
                @if(isset($settings['address']))
                <p style="margin: 1px 0;">{{ strlen($settings['address']) > 35 ? substr($settings['address'], 0, 32) . '...' : $settings['address'] }}</p>
                @endif
                @if(isset($settings['phone']))
                <p style="margin: 1px 0;">Tel: {{ $settings['phone'] }}</p>
                @endif
                @if(isset($settings['email']))
                <p style="margin: 1px 0;">Email: {{ strlen($settings['email']) > 35 ? substr($settings['email'], 0, 32) . '...' : $settings['email'] }}</p>
                @endif
                @if(isset($settings['website']))
                <p style="margin: 1px 0;">{{ strlen($settings['website']) > 35 ? substr($settings['website'], 0, 32) . '...' : $settings['website'] }}</p>
                @endif
                @if(isset($settings['kra_pin']))
                <p style="margin: 1px 0; font-weight: bold;">KRA PIN: {{ $settings['kra_pin'] }}</p>
                @endif
            </div>
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
                    <div class="value">{{ $sale->date->format('d/m/Y H:i') }}</div>
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
                    <th style="width: 28mm;">Item</th>
                    <th class="right" style="width: 8mm;">Qty</th>
                    <th class="right" style="width: 18mm;">Price</th>
                    <th class="right" style="width: 18mm;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $item)
                <tr>
                    <td style="max-width: 28mm; font-size: 8px; line-height: 1.1;">
                        {{ strlen($item->part->name) > 20 ? substr($item->part->name, 0, 17) . '...' : $item->part->name }}<br>
                        <span style="font-size: 7px;">{{ $item->part->part_number }}</span>
                    </td>
                    <td class="right" style="width: 8mm; font-size: 8px;">{{ $item->quantity }}</td>
                    <td class="right" style="width: 18mm; font-size: 8px;">KES {{ number_format($item->price, 2) }}</td>
                    <td class="right" style="width: 18mm; font-size: 8px; font-weight: bold;">KES {{ number_format($item->subtotal, 2) }}</td>
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
            <div style="font-size: 7px; color: #000; margin-top: 1px;">
                Ref: {{ strlen($payment->transaction_reference) > 20 ? substr($payment->transaction_reference, 0, 17) . '...' : $payment->transaction_reference }}
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <!-- eTIMS Information -->
        @if($sale->generate_etims_receipt)
        <div class="payment-info" style="margin-top: 4px; padding-top: 4px; border-top: 1px dashed #000;">
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
            <p style="margin-top: 5px;">Loyalty Points: {{ number_format($sale->customer->loyalty_points) }}</p>
            @endif
            <p style="margin-top: 4px; font-size: 7px;">Powered By Designekta Studios</p>
        </div>
    </div>
</body>
</html>

