@extends('layouts.app')

@section('title', 'Receipt History')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Receipt History</h1>
                    <p class="text-muted mb-0">View and manage your payment receipts</p>
                </div>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Search Receipt -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-search me-2"></i>Search Receipt
                    </h5>
                    <form action="{{ route('receipt.search') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-8">
                            <input type="text" 
                                   class="form-control @error('receipt_number') is-invalid @enderror" 
                                   name="receipt_number" 
                                   placeholder="Enter receipt number (e.g., RCPT-000001)"
                                   value="{{ old('receipt_number') }}"
                                   required>
                            @error('receipt_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-coffee w-100">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Receipts List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Recent Receipts
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($receipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Receipt #</th>
                                        <th>Order ID</th>
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
                                            <div class="fw-bold text-coffee">{{ $receipt->receipt_number_display }}</div>
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
                                                <a href="{{ route('receipt.show', $receipt->receipt_number) }}" 
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
                                                <a href="{{ route('receipt.download', $receipt->receipt_number) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Download Receipt">
                                                    <i class="bi bi-download"></i>
                                                </a>
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
                            <p class="text-muted">You haven't made any orders yet, or receipts haven't been generated.</p>
                            <a href="{{ route('menu') }}" class="btn btn-coffee">
                                <i class="bi bi-cart-plus me-2"></i>Order Now
                            </a>
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

            <!-- Receipt Statistics -->
            @if($receipts->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="h4 text-coffee mb-1">{{ $receipts->count() }}</div>
                                <small class="text-muted">Total Receipts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="h4 text-success mb-1">
                                    Rs. {{ number_format($receipts->sum('total'), 2) }}
                                </div>
                                <small class="text-muted">Total Spent</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <div class="h4 text-warning mb-1">{{ $receipts->sum('loyalty_points_earned') }}</div>
                                <small class="text-muted">Total Points Earned</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.text-coffee {
    color: #8B4513 !important;
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
