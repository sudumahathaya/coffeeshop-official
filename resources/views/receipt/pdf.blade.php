<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
            background: white;
            color: #333;
        }
        
        .receipt {
            max-width: 100%;
            margin: 0 auto;
            border: 2px solid #8B4513;
            padding: 15px;
            background: white;
            /* Optimized for landscape */
            min-height: 100vh;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .business-name {
            font-size: 22px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 5px;
        }
        
        .business-tagline {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .receipt-number {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }
        
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-weight: bold;
            color: #8B4513;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 8px;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            page-break-inside: avoid;
        }
        
        .item-row {
            border-bottom: 1px dotted #ddd;
            padding: 3px 0;
            page-break-inside: avoid;
        }
        
        .total-row {
            border-top: 2px solid #8B4513;
            padding-top: 8px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .loyalty-box {
            border: 2px dashed #FFD700;
            padding: 10px;
            text-align: center;
            margin: 15px 0;
            background: #FFF8DC;
            page-break-inside: avoid;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #666;
            page-break-inside: avoid;
        }
        
        .thank-you {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            color: #8B4513;
            font-size: 14px;
            page-break-inside: avoid;
        }
        
        /* Landscape optimized layout */
        .landscape-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .left-column {
            flex: 1;
            min-width: 0;
        }
        
        .right-column {
            flex: 1;
            min-width: 0;
        }
        
        .center-column {
            flex: 2;
            min-width: 0;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10px;
        }
        
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #8B4513;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-primary {
            color: #8B4513;
        }
        
        .text-success {
            color: #28a745;
        }
        
        .text-warning {
            color: #ffc107;
        }
        
        .badge {
            background: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .badge-success {
            background: #28a745;
        }
        
        .badge-info {
            background: #17a2b8;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="business-name">Café Elixir</div>
            <div class="business-tagline">Premium Coffee & Dining Experience</div>
            <div class="receipt-number">Receipt: {{ $receipt->receipt_number }}</div>
            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                {{ $receipt->formatted_generated_at }}
            </div>
        </div>

        <!-- Main Content in Landscape Layout -->
        <div class="landscape-grid">
            <!-- Left Column: Order & Customer Details -->
            <div class="left-column">
                <!-- Order Details -->
                <div class="section">
                    <div class="section-title">Order Details</div>
                    <div class="row">
                        <span>Order ID:</span>
                        <span><strong>{{ $receipt->order->order_id }}</strong></span>
                    </div>
                    <div class="row">
                        <span>Date:</span>
                        <span><strong>{{ $receipt->formatted_generated_at }}</strong></span>
                    </div>
                    <div class="row">
                        <span>Type:</span>
                        <span><strong>{{ ucfirst($receipt->receipt_data['order_details']['order_type']) }}</strong></span>
                    </div>
                </div>
                
                <!-- Customer Details -->
                <div class="section">
                    <div class="section-title">Customer Details</div>
                    <div class="row">
                        <span>Name:</span>
                        <span><strong>{{ $receipt->receipt_data['customer_details']['name'] }}</strong></span>
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
                
                <!-- Payment Details -->
                <div class="section">
                    <div class="section-title">Payment Details</div>
                    <div class="row">
                        <span>Method:</span>
                        <span><strong>{{ ucfirst($receipt->payment_method) }}</strong></span>
                    </div>
                    <div class="row">
                        <span>Status:</span>
                        <span><span class="badge badge-success">{{ ucfirst($receipt->payment_status) }}</span></span>
                    </div>
                    @if($receipt->transaction_id)
                    <div class="row">
                        <span>Transaction ID:</span>
                        <span style="font-family: monospace; font-size: 9px;">{{ $receipt->transaction_id }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Center Column: Order Items -->
            <div class="center-column">
                <!-- Order Items -->
                <div class="section">
                    <div class="section-title">Order Items</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipt->receipt_data['order_details']['items'] as $item)
                            <tr class="item-row">
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-right">Rs. {{ number_format($item['price'], 2) }}</td>
                                <td class="text-right">Rs. {{ number_format($item['total'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Special Instructions -->
                @if($receipt->receipt_data['order_details']['special_instructions'])
                <div class="section">
                    <div class="section-title">Special Instructions</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 3px; font-size: 10px;">
                        {{ $receipt->receipt_data['order_details']['special_instructions'] }}
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column: Business Info & Totals -->
            <div class="right-column">
                <!-- Business Information -->
                <div class="section">
                    <div class="section-title">Business Information</div>
                    <div class="row">
                        <span>Business:</span>
                        <span><strong>Café Elixir</strong></span>
                    </div>
                    <div class="row">
                        <span>Address:</span>
                        <span>{{ $receipt->receipt_data['business_details']['address'] }}</span>
                    </div>
                    <div class="row">
                        <span>Phone:</span>
                        <span>{{ $receipt->receipt_data['business_details']['phone'] }}</span>
                    </div>
                    <div class="row">
                        <span>Email:</span>
                        <span>{{ $receipt->receipt_data['business_details']['email'] }}</span>
                    </div>
                    <div class="row">
                        <span>Hours:</span>
                        <span>{{ $receipt->receipt_data['business_details']['business_hours'] }}</span>
                    </div>
                </div>
                
                <!-- Payment Summary -->
                <div class="section">
                    <div class="section-title">Payment Summary</div>
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
                        <span class="text-success">-{{ $receipt->formatted_discount }}</span>
                    </div>
                    @endif
                    <div class="row total-row">
                        <span>Total:</span>
                        <span class="text-primary">{{ $receipt->formatted_total }}</span>
                    </div>
                </div>
                
                <!-- Loyalty Points -->
                @if($receipt->loyalty_points_earned > 0)
                <div class="loyalty-box">
                    <div style="font-weight: bold; color: #B8860B; margin-bottom: 5px;">
                        <i class="bi bi-star-fill"></i> Loyalty Points Earned
                    </div>
                    <div style="font-size: 16px; color: #B8860B;">
                        +{{ $receipt->loyalty_points_earned }} Points
                    </div>
                    <div style="font-size: 9px; color: #666; margin-top: 5px;">
                        Thank you for your loyalty!
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Thank you for choosing Café Elixir!</div>
            <div>For any questions, please contact us at {{ $receipt->receipt_data['business_details']['phone'] }}</div>
            <div>Visit us at {{ $receipt->receipt_data['business_details']['website'] }}</div>
            <div style="margin-top: 10px; font-size: 8px; color: #999;">
                Receipt generated on {{ $receipt->formatted_generated_at }} | 
                Receipt ID: {{ $receipt->id }} | 
                Status: {{ ucfirst($receipt->status) }}
            </div>
        </div>
    </div>
</body>
</html>
