@extends('layouts.app')

@section('title', 'Payment Receipt - ' . $receipt->receipt_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Receipt Header -->
            <div class="text-center mb-4">
                <h1 class="h3 text-success mb-2">
                    <i class="bi bi-check-circle-fill me-2"></i>Payment Successful!
                </h1>
                <p class="text-muted">Your receipt has been generated</p>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Receipt Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="{{ asset('img/coffee.png') }}" alt="Café Elixir" height="40" class="me-3">
                        <div>
                            <h4 class="mb-0 text-coffee fw-bold">Café Elixir</h4>
                            <small class="text-muted">Premium Coffee & Dining</small>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <h6 class="text-muted mb-1">Receipt Number</h6>
                        <h5 class="text-dark fw-bold">{{ $receipt->receipt_number_display }}</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Order Details -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted d-block">Order ID</small>
                            <strong>{{ $receipt->order->order_id }}</strong>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted d-block">Date & Time</small>
                            <strong>{{ $receipt->formatted_generated_at }}</strong>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="border-top border-bottom py-3 mb-4">
                        <h6 class="text-coffee mb-3">Customer Details</h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Name</small>
                                <strong>{{ $receipt->receipt_data['customer_details']['name'] }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Order Type</small>
                                <strong class="text-capitalize">{{ $receipt->receipt_data['order_details']['order_type'] }}</strong>
                            </div>
                        </div>
                        @if($receipt->receipt_data['customer_details']['email'])
                        <div class="row mt-2">
                            <div class="col-6">
                                <small class="text-muted d-block">Email</small>
                                <strong>{{ $receipt->receipt_data['customer_details']['email'] }}</strong>
                            </div>
                            @if($receipt->receipt_data['customer_details']['phone'])
                            <div class="col-6">
                                <small class="text-muted d-block">Phone</small>
                                <strong>{{ $receipt->receipt_data['customer_details']['phone'] }}</strong>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h6 class="text-coffee mb-3">Order Items</h6>
                        @foreach($receipt->receipt_data['order_details']['items'] as $item)
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <small class="text-muted d-block">Qty: {{ $item['quantity'] }}</small>
                            </div>
                            <div class="text-end">
                                <strong>Rs. {{ number_format($item['total'], 2) }}</strong>
                                <small class="text-muted d-block">@ Rs. {{ number_format($item['price'], 2) }}/each</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Special Instructions -->
                    @if($receipt->receipt_data['order_details']['special_instructions'])
                    <div class="border-top border-bottom py-3 mb-4">
                        <h6 class="text-coffee mb-2">Special Instructions</h6>
                        <p class="text-muted mb-0">{{ $receipt->receipt_data['order_details']['special_instructions'] }}</p>
                    </div>
                    @endif

                    <!-- Payment Summary -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ $receipt->formatted_subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%)</span>
                            <span>{{ $receipt->formatted_tax }}</span>
                        </div>
                        @if($receipt->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span class="text-success">-{{ $receipt->formatted_discount }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Items</span>
                            <span>{{ $receipt->receipt_data['order_details']['total_items'] }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong class="h5 mb-0">Total Amount</strong>
                            <strong class="h5 mb-0 text-coffee">{{ $receipt->formatted_total }}</strong>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="border-top pt-3 mt-4">
                        <h6 class="text-coffee mb-3">Payment Details</h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Payment Method</small>
                                <strong class="text-capitalize">{{ $receipt->payment_method }}</strong>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block">Status</small>
                                <span class="badge bg-success">{{ ucfirst($receipt->payment_status) }}</span>
                            </div>
                        </div>
                        @if($receipt->transaction_id)
                        <div class="row mt-2">
                            <div class="col-12">
                                <small class="text-muted d-block">Transaction ID</small>
                                <strong class="font-monospace">{{ $receipt->transaction_id }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Loyalty Points -->
                    @if($receipt->loyalty_points_earned > 0)
                    <div class="border-top pt-3 mt-4">
                        <div class="text-center">
                            <div class="bg-light rounded p-3">
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <strong class="text-coffee">Loyalty Points Earned!</strong>
                                <div class="h4 text-warning mb-0 mt-2">{{ $receipt->loyalty_points_earned }} points</div>
                                <small class="text-muted">Added to your account</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Receipt Footer -->
                <div class="card-footer bg-light text-center py-3">
                    <div class="row">
                        <div class="col-4">
                            <a href="{{ route('receipt.print', $receipt->receipt_number) }}" 
                               class="btn btn-outline-secondary btn-sm w-100" target="_blank">
                                <i class="bi bi-printer me-1"></i>Print
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('receipt.download', $receipt->receipt_number) }}" 
                               class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('dashboard') }}" 
                               class="btn btn-coffee btn-sm w-100">
                                <i class="bi bi-house me-1"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <strong>Café Elixir</strong><br>
                    {{ $receipt->receipt_data['business_details']['address'] }}<br>
                    Phone: {{ $receipt->receipt_data['business_details']['phone'] }}<br>
                    Email: {{ $receipt->receipt_data['business_details']['email'] }}<br>
                    Business Hours: {{ $receipt->receipt_data['business_details']['business_hours'] }}
                </small>
            </div>

            <!-- Thank You Message -->
            <div class="text-center mt-4">
                <div class="alert alert-success border-0">
                    <i class="bi bi-heart-fill text-danger me-2"></i>
                    <strong>Thank you for choosing Café Elixir!</strong><br>
                    <small class="text-muted">We hope you enjoy your order. Visit us again soon!</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-coffee {
    color: #8B4513 !important;
}
.bg-coffee {
    background-color: #8B4513 !important;
}
.btn-coffee {
    background-color: #8B4513;
    border-color: #8B4513;
    color: white;
}
.btn-coffee:hover {
    background-color: #654321;
    border-color: #654321;
    color: white;
}
</style>
@endsection
