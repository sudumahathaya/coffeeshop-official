<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $receipt_info['receipt_number'] }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            color: #333;
        }

        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .cafe-logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .cafe-name {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .cafe-tagline {
            font-size: 1rem;
            opacity: 0.9;
        }

        .receipt-body {
            padding: 2rem;
        }

        .receipt-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #666;
        }

        .info-value {
            color: #8B4513;
            font-weight: 600;
        }

        .order-items {
            margin: 2rem 0;
        }

        .items-header {
            background: #8B4513;
            color: white;
            padding: 1rem;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 0 0 10px 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .items-table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #8B4513;
            border-bottom: 2px solid #e9ecef;
        }

        .items-table td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            color: #333;
        }

        .item-price {
            text-align: right;
            font-weight: 600;
        }

        .totals-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding: 0.5rem 0;
        }

        .total-row.final {
            border-top: 2px solid #8B4513;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: #8B4513;
        }

        .payment-info {
            background: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 0 10px 10px 0;
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #28a745;
        }

        .footer-info {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
            color: #666;
        }

        .cafe-contact {
            margin-bottom: 1rem;
        }

        .thank-you {
            font-size: 1.1rem;
            font-weight: 600;
            color: #8B4513;
            margin-bottom: 1rem;
        }

        .qr-code {
            margin: 1rem 0;
        }

        .loyalty-points {
            background: linear-gradient(45deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.2));
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            border: 2px solid #ffc107;
            text-align: center;
        }

        .points-earned {
            font-size: 1.5rem;
            font-weight: bold;
            color: #856404;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
        }

        @media (max-width: 600px) {
            .receipt-container {
                margin: 0;
                border-radius: 0;
            }
            
            .receipt-header,
            .receipt-body {
                padding: 1rem;
            }
            
            .cafe-name {
                font-size: 1.5rem;
            }
            
            .items-table th,
            .items-table td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="cafe-logo">☕</div>
            <div class="cafe-name">{{ $cafe_info['name'] }}</div>
            <div class="cafe-tagline">Premium Coffee Experience</div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Receipt Information -->
            <div class="receipt-info">
                <div class="info-row">
                    <span class="info-label">Receipt Number:</span>
                    <span class="info-value">{{ $receipt_info['receipt_number'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order ID:</span>
                    <span class="info-value">#{{ $order->order_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date & Time:</span>
                    <span class="info-value">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                @if($order->customer_email)
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
                @endif
                @if($order->customer_phone)
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $order->customer_phone }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Order Type:</span>
                    <span class="info-value">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="order-items">
                <div class="items-header">
                    Order Items
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Unit Price</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(is_array($order->items))
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="item-name">{{ $item['name'] }}</div>
                                </td>
                                <td style="text-align: center;">{{ $item['quantity'] }}</td>
                                <td class="item-price">Rs. {{ number_format($item['price'], 2) }}</td>
                                <td class="item-price">Rs. {{ number_format($item['total'], 2) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Special Instructions -->
            @if($order->special_instructions)
            <div class="special-instructions">
                <h6 style="color: #8B4513; margin-bottom: 0.5rem;">Special Instructions:</h6>
                <p style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin: 0;">
                    {{ $order->special_instructions }}
                </p>
            </div>
            @endif

            <!-- Payment Information -->
            @if($transaction)
            <div class="payment-info">
                <div class="payment-method">
                    <span>💳</span>
                    <span>Payment Method: {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                </div>
                @if($transaction->transaction_id)
                <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                    Transaction ID: {{ $transaction->transaction_id }}
                </div>
                @endif
                <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                    Status: <span style="color: #28a745; font-weight: bold;">{{ ucfirst($transaction->status) }}</span>
                </div>
            </div>
            @else
            <div class="payment-info">
                <div class="payment-method">
                    <span>💵</span>
                    <span>Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                </div>
                <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                    Status: <span style="color: #28a745; font-weight: bold;">{{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>
            @endif

            <!-- Totals -->
            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rs. {{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax ({{ $totals['tax_rate'] }}%):</span>
                    <span>Rs. {{ number_format($totals['tax'], 2) }}</span>
                </div>
                @if($transaction && $transaction->processing_fee > 0)
                <div class="total-row">
                    <span>Processing Fee:</span>
                    <span>Rs. {{ number_format($transaction->processing_fee, 2) }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>Total Amount:</span>
                    <span>Rs. {{ number_format($totals['total'], 2) }}</span>
                </div>
            </div>

            <!-- Loyalty Points -->
            @if($order->user_id && $order->loyaltyPoints)
            <div class="loyalty-points">
                <div style="margin-bottom: 0.5rem;">🌟 Loyalty Points Earned</div>
                <div class="points-earned">+{{ $order->loyaltyPoints->where('type', 'earned')->sum('points') }} Points</div>
                <div style="font-size: 0.9rem; margin-top: 0.5rem;">Thank you for being a valued member!</div>
            </div>
            @endif

            <!-- Footer -->
            <div class="footer-info">
                <div class="thank-you">Thank you for choosing Café Elixir!</div>
                
                <div class="cafe-contact">
                    <div><strong>{{ $cafe_info['name'] }}</strong></div>
                    <div>{{ $cafe_info['address'] }}</div>
                    <div>Phone: {{ $cafe_info['phone'] }}</div>
                    <div>Email: {{ $cafe_info['email'] }}</div>
                    <div>Web: {{ $cafe_info['website'] }}</div>
                </div>

                <div style="margin-top: 1.5rem; font-size: 0.9rem;">
                    <div>Receipt generated on {{ $receipt_info['generated_at']->format('M d, Y g:i A') }}</div>
                    @if(isset($receipt_info['generated_by']))
                    <div>Generated by: {{ $receipt_info['generated_by'] }}</div>
                    @endif
                </div>

                <div style="margin-top: 1rem; font-size: 0.8rem; color: #999;">
                    This is a computer-generated receipt. No signature required.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-print when viewing receipt directly
        if (window.location.pathname.includes('/receipt/') && !window.location.search.includes('download')) {
            window.onload = function() {
                setTimeout(() => {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>