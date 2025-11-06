<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
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
        .customer-info {
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🛒 New Order Received</h1>
        <p>Order #{{ $order->order_number }}</p>
    </div>
    
    <div class="content">
        <div class="customer-info">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $customer_name }}</p>
            <p><strong>Email:</strong> {{ $customer_email }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
            @if($order->notes)
                <p><strong>Notes:</strong> {{ $order->notes }}</p>
            @endif
        </div>
        
        <div class="order-details">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span></p>
            <p><strong>Payment Status:</strong> <span class="status-badge status-pending">{{ ucfirst($order->payment_status) }}</span></p>
            <p><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
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
        
        <div style="text-align: center; margin-top: 30px;">
            <p style="color: #666; font-size: 14px;">
                This order has been automatically created in your admin panel.<br>
                Please review and process it accordingly.
            </p>
        </div>
    </div>
</body>
</html> 