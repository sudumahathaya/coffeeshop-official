<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .business-name {
            font-size: 18px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 5px;
        }
        
        .business-tagline {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .receipt-number {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-weight: bold;
            color: #8B4513;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .item-row {
            border-bottom: 1px dotted #ddd;
            padding: 5px 0;
        }
        
        .total-row {
            border-top: 2px solid #8B4513;
            padding-top: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .loyalty-box {
            border: 2px dashed #FFD700;
            padding: 10px;
            text-align: center;
            margin: 15px 0;
            background: #FFF8DC;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        
        .thank-you {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            color: #8B4513;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #8B4513;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #654321;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">Print Receipt</button>
    
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="business-name">Café Elixir</div>
            <div class="business-tagline">Premium Coffee & Dining</div>
            <div class="receipt-number">Receipt: {{ $receipt->receipt_number_display }}</div>
        </div>
        
        <!-- Order Details -->
        <div class="section">
            <div class="section-title">ORDER DETAILS</div>
            <div class="row">
                <span>Order ID:</span>
                <span>{{ $receipt->order->order_id }}</span>
            </div>
            <div class="row">
                <span>Date:</span>
                <span>{{ $receipt->formatted_generated_at }}</span>
            </div>
            <div class="row">
                <span>Type:</span>
                <span>{{ ucfirst($receipt->receipt_data['order_details']['order_type']) }}</span>
            </div>
        </div>
        
        <!-- Customer Details -->
        <div class="section">
            <div class="section-title">CUSTOMER DETAILS</div>
            <div class="row">
                <span>Name:</span>
                <span>{{ $receipt->receipt_data['customer_details']['name'] }}</span>
            </div>
            @if($receipt->receipt_data['customer_details']['email'])
            <div class="row">
                <span>Email:</span>
                <span>{{ $receipt->receipt_data['customer_details']['email'] }}</span>
            </div>
            @endif
            @if($receipt->receipt_data['customer_details']['phone'])
            <div class="row">
                <span>Phone:</span>
                <span>{{ $receipt->receipt_data['customer_details']['phone'] }}</span>
            </div>
            @endif
        </div>
        
        <!-- Order Items -->
        <div class="section">
            <div class="section-title">ORDER ITEMS</div>
            @foreach($receipt->receipt_data['order_details']['items'] as $item)
            <div class="item-row">
                <div class="row">
                    <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                    <span>Rs. {{ number_format($item['total'], 2) }}</span>
                </div>
                <div style="text-align: right; font-size: 10px; color: #666;">
                    @ Rs. {{ number_format($item['price'], 2) }} each
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Special Instructions -->
        @if($receipt->receipt_data['order_details']['special_instructions'])
        <div class="section">
            <div class="section-title">SPECIAL INSTRUCTIONS</div>
            <div style="padding: 5px 0;">{{ $receipt->receipt_data['order_details']['special_instructions'] }}</div>
        </div>
        @endif
        
        <!-- Payment Summary -->
        <div class="section">
            <div class="section-title">PAYMENT SUMMARY</div>
            <div class="row">
                <span>Subtotal:</span>
                <span>{{ $receipt->formatted_subtotal }}</span>
            </div>
            <div class="row">
                <span>Tax (10%):</span>
                <span>{{ $receipt->formatted_tax }}</span>
            </div>
            @if($receipt->discount > 0)
            <div class="row">
                <span>Discount:</span>
                <span>-{{ $receipt->formatted_discount }}</span>
            </div>
            @endif
            <div class="row">
                <span>Total Items:</span>
                <span>{{ $receipt->receipt_data['order_details']['total_items'] }}</span>
            </div>
            <div class="row total-row">
                <span>TOTAL:</span>
                <span>{{ $receipt->formatted_total }}</span>
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="section">
            <div class="section-title">PAYMENT DETAILS</div>
            <div class="row">
                <span>Method:</span>
                <span>{{ ucfirst($receipt->payment_method) }}</span>
            </div>
            <div class="row">
                <span>Status:</span>
                <span>{{ ucfirst($receipt->payment_status) }}</span>
            </div>
            @if($receipt->transaction_id)
            <div class="row">
                <span>Transaction ID:</span>
                <span style="font-size: 10px;">{{ $receipt->transaction_id }}</span>
            </div>
            @endif
        </div>
        
        <!-- Loyalty Points -->
        @if($receipt->loyalty_points_earned > 0)
        <div class="loyalty-box">
            <div style="font-weight: bold; color: #8B4513;">LOYALTY POINTS EARNED!</div>
            <div style="font-size: 18px; color: #FFD700; font-weight: bold;">{{ $receipt->loyalty_points_earned }} points</div>
            <div style="font-size: 10px; color: #666;">Added to your account</div>
        </div>
        @endif
        
        <!-- Thank You -->
        <div class="thank-you">
            Thank you for choosing Café Elixir!
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div style="font-weight: bold; margin-bottom: 5px;">Café Elixir</div>
            <div>{{ $receipt->receipt_data['business_details']['address'] }}</div>
            <div>Phone: {{ $receipt->receipt_data['business_details']['phone'] }}</div>
            <div>Email: {{ $receipt->receipt_data['business_details']['email'] }}</div>
            <div>Business Hours: {{ $receipt->receipt_data['business_details']['business_hours'] }}</div>
            <div style="margin-top: 10px;">This is a computer-generated receipt</div>
        </div>
    </div>
</body>
</html>
