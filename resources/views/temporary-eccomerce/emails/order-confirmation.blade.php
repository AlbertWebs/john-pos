<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #7b2c2c;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #7b2c2c;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            color: #7b2c2c;
        }
        .delivery-info {
            background-color: #e8f4fd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .next-steps {
            background-color: #d1ecf1;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Order Confirmation</h1>
        <p>Thank you for your order!</p>
    </div>
    
    <div class="content">
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span></p>
            <p><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
        </div>
        
        <div class="delivery-info">
            <h3>Delivery Information</h3>
            <p><strong>Delivery Address:</strong></p>
            <p>{{ $order->shipping_address }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            @if($order->notes)
                <p><strong>Delivery Notes:</strong> {{ $order->notes }}</p>
            @endif
        </div>
        
        <div class="order-details">
            <h3>Order Items</h3>
            @foreach($items as $item)
                <div class="item">
                    <div>
                        <strong>{{ $item['product']->name }}</strong><br>
                        <small>Quantity: {{ $item['quantity'] }}</small>
                    </div>
                    <div>
                        <strong>KES {{ number_format($item['subtotal'], 2) }}</strong>
                    </div>
                </div>
            @endforeach
            
            <hr style="margin: 15px 0;">
            <div class="item total">
                <div><strong>Total Amount</strong></div>
                <div><strong>KES {{ number_format($order->total_amount, 2) }}</strong></div>
            </div>
        </div>
        
        <div class="next-steps">
            <h3>What Happens Next?</h3>
            <ol style="margin: 10px 0; padding-left: 20px;">
                <li>We will review your order and contact you within 24 hours</li>
                <li>We will confirm your delivery details and preferred delivery time</li>
                <li>Your order will be prepared and delivered to your address</li>
                <li>Payment will be collected upon delivery (cash or mobile money)</li>
            </ol>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <p style="color: #666; font-size: 14px;">
                If you have any questions about your order, please contact us.<br>
                Thank you for choosing our store!
            </p>
        </div>
    </div>
</body>
</html> 