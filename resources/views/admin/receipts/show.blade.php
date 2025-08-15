@extends('layouts.admin')

@section('title', 'Receipt Details - ' . $receipt->receipt_number)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Receipt Details</h1>
            <p class="text-muted mb-0">View detailed information for receipt {{ $receipt->receipt_number_display }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.receipts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Receipts
            </a>
            <a href="{{ route('receipt.print', $receipt->receipt_number) }}" 
               class="btn btn-outline-info" target="_blank">
                <i class="bi bi-printer me-2"></i>Print
            </a>
            @if($receipt->status === 'active')
            <button type="button" class="btn btn-outline-danger" onclick="cancelReceipt({{ $receipt->id }})">
                <i class="bi bi-x-circle me-2"></i>Cancel Receipt
            </button>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Receipt Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Receipt Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Receipt Number</label>
                                <div class="h5 text-primary">{{ $receipt->receipt_number_display }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    @if($receipt->status === 'active')
                                        <span class="badge bg-success fs-6">Active</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Cancelled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Order ID</label>
                                <div class="h6">{{ $receipt->order->order_id }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Generated At</label>
                                <div class="h6">{{ $receipt->formatted_generated_at }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Method</label>
                                <div class="h6 text-capitalize">{{ $receipt->payment_method }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Status</label>
                                <div>
                                    <span class="badge bg-success">{{ ucfirst($receipt->payment_status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($receipt->transaction_id)
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Transaction ID</label>
                                <div class="h6 font-monospace">{{ $receipt->transaction_id }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Customer Name</label>
                                <div class="h6">{{ $receipt->receipt_data['customer_details']['name'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Order Type</label>
                                <div class="h6 text-capitalize">{{ $receipt->receipt_data['order_details']['order_type'] }}</div>
                            </div>
                        </div>
                    </div>

                    @if($receipt->receipt_data['customer_details']['email'])
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <div class="h6">{{ $receipt->receipt_data['customer_details']['email'] }}</div>
                            </div>
                        </div>
                        @if($receipt->receipt_data['customer_details']['phone'])
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone</label>
                                <div class="h6">{{ $receipt->receipt_data['customer_details']['phone'] }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($receipt->receipt_data['order_details']['special_instructions'])
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Special Instructions</label>
                                <div class="h6">{{ $receipt->receipt_data['order_details']['special_instructions'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>Order Items
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($receipt->receipt_data['order_details']['items'] as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item['name'] }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item['quantity'] }}</span>
                                    </td>
                                    <td>Rs. {{ number_format($item['price'], 2) }}</td>
                                    <td class="text-end fw-bold">Rs. {{ number_format($item['total'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary & Actions -->
        <div class="col-lg-4">
            <!-- Payment Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calculator me-2"></i>Payment Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>{{ $receipt->formatted_subtotal }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>{{ $receipt->formatted_tax }}</span>
                    </div>
                    @if($receipt->discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span class="text-success">-{{ $receipt->formatted_discount }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Items:</span>
                        <span>{{ $receipt->receipt_data['order_details']['total_items'] }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong class="h5 mb-0">Total Amount</strong>
                        <strong class="h5 mb-0 text-primary">{{ $receipt->formatted_total }}</strong>
                    </div>
                </div>
            </div>

            <!-- Loyalty Points -->
            @if($receipt->loyalty_points_earned > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star me-2"></i>Loyalty Points
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="h3 text-warning mb-2">{{ $receipt->loyalty_points_earned }}</div>
                    <small class="text-muted">Points Earned</small>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('receipt.print', $receipt->receipt_number) }}" 
                           class="btn btn-outline-secondary" target="_blank">
                            <i class="bi bi-printer me-2"></i>Print Receipt
                        </a>
                        <a href="{{ route('receipt.download', $receipt->receipt_number) }}" 
                           class="btn btn-outline-info">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </a>
                        @if($receipt->status === 'active')
                        <button type="button" class="btn btn-outline-danger" onclick="cancelReceipt({{ $receipt->id }})">
                            <i class="bi bi-x-circle me-2"></i>Cancel Receipt
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building me-2"></i>Business Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Café Elixir</strong>
                    </div>
                    <div class="small text-muted mb-2">
                        {{ $receipt->receipt_data['business_details']['address'] }}
                    </div>
                    <div class="small text-muted mb-2">
                        Phone: {{ $receipt->receipt_data['business_details']['phone'] }}
                    </div>
                    <div class="small text-muted mb-2">
                        Email: {{ $receipt->receipt_data['business_details']['email'] }}
                    </div>
                    <div class="small text-muted">
                        Hours: {{ $receipt->receipt_data['business_details']['business_hours'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Receipt Modal -->
<div class="modal fade" id="cancelReceiptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this receipt? This action cannot be undone.</p>
                <p class="text-muted">The receipt will be marked as cancelled but the order will remain unchanged.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Receipt</button>
            </div>
        </div>
    </div>
</div>

<script>
let receiptToCancel = null;

// Cancel receipt functionality
function cancelReceipt(receiptId) {
    receiptToCancel = receiptId;
    const modal = new bootstrap.Modal(document.getElementById('cancelReceiptModal'));
    modal.show();
}

document.getElementById('confirmCancelBtn').addEventListener('click', function() {
    if (receiptToCancel) {
        fetch(`/admin/receipts/${receiptToCancel}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect back to receipts list
                window.location.href = '{{ route("admin.receipts.index") }}';
            } else {
                alert('Failed to cancel receipt: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the receipt');
        });
    }
});
</script>
@endsection
