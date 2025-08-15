@extends('layouts.admin')

@section('title', 'Receipt Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Receipt Management</h1>
            <p class="text-muted mb-0">View and manage all payment receipts</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.receipts.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">All Methods</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="mobile" {{ request('payment_method') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="digital_wallet" {{ request('payment_method') == 'digital_wallet' ? 'selected' : '' }}>Digital Wallet</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipts Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-receipt me-2"></i>All Receipts
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportReceipts()">
                        <i class="bi bi-download me-2"></i>Export
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="printReceipts()">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($receipts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="receiptsTable">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Receipt #</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipts as $receipt)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input receipt-checkbox" 
                                           value="{{ $receipt->id }}">
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $receipt->receipt_number_display }}</div>
                                    @if($receipt->loyalty_points_earned > 0)
                                        <small class="text-success">
                                            <i class="bi bi-star-fill me-1"></i>{{ $receipt->loyalty_points_earned }} pts
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $receipt->order->order_id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $receipt->receipt_data['customer_details']['name'] }}</div>
                                    <small class="text-muted">{{ $receipt->receipt_data['customer_details']['email'] ?? 'No email' }}</small>
                                </td>
                                <td>
                                    <div>{{ $receipt->formatted_generated_at }}</div>
                                    <small class="text-muted">{{ $receipt->generated_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $receipt->formatted_total }}</div>
                                    <small class="text-muted">{{ $receipt->receipt_data['order_details']['total_items'] }} items</small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-capitalize">{{ $receipt->payment_method }}</span>
                                </td>
                                <td>
                                    @if($receipt->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.receipts.show', $receipt->receipt_number) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View Receipt">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('receipt.print', $receipt->receipt_number) }}" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           target="_blank"
                                           title="Print Receipt">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        @if($receipt->status === 'active')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="cancelReceipt({{ $receipt->id }})"
                                                title="Cancel Receipt">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No receipts found</h5>
                    <p class="text-muted">No receipts match the current filters.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($receipts->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $receipts->links() }}
        </div>
    @endif

    <!-- Summary Statistics -->
    @if($receipts->count() > 0)
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <div class="h4 mb-1">{{ $receipts->count() }}</div>
                        <small>Total Receipts</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <div class="h4 mb-1">
                            Rs. {{ number_format($receipts->sum('total'), 2) }}
                        </div>
                        <small>Total Revenue</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <div class="h4 mb-1">{{ $receipts->sum('loyalty_points_earned') }}</div>
                        <small>Total Points Awarded</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <div class="h4 mb-1">{{ $receipts->where('status', 'active')->count() }}</div>
                        <small>Active Receipts</small>
                    </div>
                </div>
            </div>
        </div>
    @endif
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

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.receipt-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

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
                // Reload the page to show updated status
                location.reload();
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

// Export functionality
function exportReceipts() {
    const selectedReceipts = Array.from(document.querySelectorAll('.receipt-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedReceipts.length === 0) {
        alert('Please select receipts to export');
        return;
    }
    
    // For now, just show an alert. In a real implementation, this would generate a CSV/Excel file
    alert(`Exporting ${selectedReceipts.length} receipts...`);
}

// Print functionality
function printReceipts() {
    const selectedReceipts = Array.from(document.querySelectorAll('.receipt-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedReceipts.length === 0) {
        alert('Please select receipts to print');
        return;
    }
    
    // For now, just show an alert. In a real implementation, this would open print dialog
    alert(`Printing ${selectedReceipts.length} receipts...`);
}
</script>
@endsection
