@extends('layouts.admin')

@section('title', 'Orders - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Orders</h1>
            <p class="mb-0 text-muted">Manage customer orders and track sales</p>
        </div>
        <div>
            <button class="btn btn-coffee">
                <i class="bi bi-plus-circle me-2"></i>New Order
            </button>
        </div>
    </div>

    <!-- Order Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-icon bg-info mx-auto mb-3">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <h4 class="mb-0">47</h4>
                    <small class="text-muted">Today's Orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-icon bg-warning mx-auto mb-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4 class="mb-0">12</h4>
                    <small class="text-muted">Preparing</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-icon bg-success mx-auto mb-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h4 class="mb-0">35</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="stat-icon bg-primary mx-auto mb-3">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <h4 class="mb-0">Rs. 45,200</h4>
                    <small class="text-muted">Today's Revenue</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>All Status</option>
                        <option>Pending</option>
                        <option>Preparing</option>
                        <option>Ready</option>
                        <option>Completed</option>
                    </select>
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <span class="fw-bold text-coffee">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0">{{ $order->customer_name }}</h6>
                                    <small class="text-muted">Walk-in Customer</small>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $order->items }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">Rs. {{ number_format($order->total, 2) }}</span>
                            </td>
                            <td>
                                @if($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status == 'preparing')
                                    <span class="badge bg-warning">Preparing</span>
                                @elseif($order->status == 'ready')
                                    <span class="badge bg-info">Ready</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($order->status == 'preparing')
                                        <button class="btn btn-success btn-sm" onclick="markReady('{{ $order->id }}')">
                                            <i class="bi bi-check"></i> Ready
                                        </button>
                                    @elseif($order->status == 'ready')
                                        <button class="btn btn-primary btn-sm" onclick="markCompleted('{{ $order->id }}')">
                                            <i class="bi bi-check-all"></i> Complete
                                        </button>
                                    @elseif($order->status == 'pending')
                                        <button class="btn btn-warning btn-sm" onclick="confirmOrder('{{ $order->id }}')">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="cancelOrder('{{ $order->id }}')">
                                            <i class="bi bi-check-all"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-secondary btn-sm" onclick="viewOrder('{{ $order->id }}')">
                                            <i class="bi bi-check"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" onclick="printReceipt('{{ $order->id }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteOrder('{{ $order->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderModalBody">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-coffee">Print Receipt</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmOrder(orderId) {
    if (confirm('Confirm this order?')) {
        updateOrderStatus(orderId, 'confirmed');
    }
}

function cancelOrder(orderId) {
    if (confirm('Cancel this order? This action cannot be undone.')) {
        updateOrderStatus(orderId, 'cancelled');
    }
}

function markReady(orderId) {
    if (confirm('Mark this order as ready for pickup?')) {
        updateOrderStatus(orderId, 'ready');
    }
}

function markCompleted(orderId) {
    if (confirm('Mark this order as completed?')) {
        updateOrderStatus(orderId, 'completed');
    }
}

function updateOrderStatus(orderId, status) {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    button.disabled = true;

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`Order status updated to ${status}!`, 'success');
            
            // Update the status badge in the table
            const row = button.closest('tr');
            const statusBadge = row.querySelector('.badge');
            if (statusBadge) {
                statusBadge.className = `badge bg-${getStatusColor(status)}`;
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
            
            // Update action buttons
            updateActionButtons(row, status);
        } else {
            showNotification('Failed to update order status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while updating the order', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function getStatusColor(status) {
    const colors = {
        'pending': 'secondary',
        'confirmed': 'info',
        'preparing': 'warning',
        'ready': 'primary',
        'completed': 'success',
        'cancelled': 'danger'
    };
    return colors[status] || 'secondary';
}

function updateActionButtons(row, status) {
    const actionsCell = row.querySelector('.btn-group');
    let buttonsHtml = '';
    
    const orderId = row.querySelector('.fw-bold').textContent.replace('#', '');
    
    if (status === 'confirmed') {
        buttonsHtml = `
            <button class="btn btn-warning btn-sm" onclick="updateOrderStatus('${orderId}', 'preparing')">
                <i class="bi bi-clock"></i> Start Preparing
            </button>
        `;
    } else if (status === 'preparing') {
        buttonsHtml = `
            <button class="btn btn-success btn-sm" onclick="markReady('${orderId}')">
                <i class="bi bi-check"></i> Ready
            </button>
        `;
    } else if (status === 'ready') {
        buttonsHtml = `
            <button class="btn btn-primary btn-sm" onclick="markCompleted('${orderId}')">
                <i class="bi bi-check-all"></i> Complete
            </button>
        `;
    }
    
    // Always add view and print buttons
    buttonsHtml += `
        <button class="btn btn-outline-secondary btn-sm" onclick="viewOrder('${orderId}')">
            <i class="bi bi-eye"></i>
        </button>
        <button class="btn btn-outline-primary btn-sm" onclick="printReceipt('${orderId}')">
            <i class="bi bi-printer"></i>
        </button>
    `;
    
    actionsCell.innerHTML = buttonsHtml;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed notification-toast`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 350px;
        border-radius: 15px;
        animation: slideInRight 0.5s ease;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    `;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'error' ? 'exclamation-triangle-fill' : 'info-circle-fill'} me-2"></i>
            <span class="flex-grow-1">${message}</span>
            <button type="button" class="btn-close ms-2" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.5s ease';
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
    }
}

function viewOrder(orderId) {
    const modalBody = document.getElementById('orderModalBody');
    modalBody.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Order Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Order ID:</strong></td><td>#${orderId}</td></tr>
                    <tr><td><strong>Customer:</strong></td><td>Alice Johnson</td></tr>
                    <tr><td><strong>Order Time:</strong></td><td>2:30 PM</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-success">Completed</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Order Items</h6>
                <table class="table table-sm">
                    <tr><td>Cappuccino</td><td>x2</td><td>Rs. 960.00</td></tr>
                    <tr><td>Croissant</td><td>x1</td><td>Rs. 280.00</td></tr>
                    <tr><td colspan="2"><strong>Total:</strong></td><td><strong>Rs. 1,240.00</strong></td></tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Special Instructions</h6>
                <p class="text-muted">Extra hot cappuccino, no sugar.</p>
            </div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('orderModal'));
    modal.show();
}

function printReceipt(orderId) {
    showNotification('Receipt printing functionality coming soon!', 'info');
}

function deleteOrder(orderId) {
    if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        return;
    }

    fetch(`/admin/orders/${orderId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Order deleted successfully!', 'success');
            // Remove the row from table
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (row) {
                row.remove();
            }
        } else {
            showNotification('Failed to delete order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while deleting the order', 'error');
    });
}

// Auto-refresh orders every 30 seconds
setInterval(function() {
    refreshOrdersTable();
}, 30000);

function refreshOrdersTable() {
    fetch('/admin/api/orders')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update orders table with new data
                console.log('Orders refreshed');
            }
        })
        .catch(error => {
            console.error('Failed to refresh orders:', error);
        });
}

// Real-time updates using WebSockets (if available)
// Real-time updates using WebSockets (if available)
// Uncomment when Pusher/Echo is configured
// if (typeof Echo !== 'undefined') {
//     Echo.channel('orders')
//         .listen('OrderUpdated', (e) => {
//             console.log('Order updated:', e);
//             updateOrderInTable(e);
//             showNotification(`Order ${e.order_id} status updated to ${e.status}`, 'info');
//         });
// }

function updateOrderInTable(orderData) {
    const row = document.querySelector(`tr[data-order-id="${orderData.id}"]`);
    if (row) {
        // Update status badge
        const statusCell = row.querySelector('td:nth-child(5)');
        if (statusCell) {
            let badgeClass = 'bg-secondary';
            switch(orderData.status) {
                case 'completed': badgeClass = 'bg-success'; break;
                case 'preparing': badgeClass = 'bg-warning'; break;
                case 'ready': badgeClass = 'bg-info'; break;
                case 'confirmed': badgeClass = 'bg-primary'; break;
                case 'cancelled': badgeClass = 'bg-danger'; break;
            }
            statusCell.innerHTML = `<span class="badge ${badgeClass}">${orderData.status.charAt(0).toUpperCase() + orderData.status.slice(1)}</span>`;
        }
        
        // Update action buttons based on new status
        updateOrderActionButtons(row, orderData.status, orderData.id);
        
        // Add visual feedback
        row.style.backgroundColor = '#d1ecf1';
        setTimeout(() => {
            row.style.backgroundColor = '';
        }, 2000);
    }
}

function updateOrderActionButtons(row, status, orderId) {
    const actionsCell = row.querySelector('td:nth-child(7) .btn-group');
    if (!actionsCell) return;
    
    let buttonsHtml = '';
    
    if (status === 'pending') {
        buttonsHtml += `
            <button class="btn btn-success btn-sm" onclick="confirmOrder('${orderId}')">
                <i class="bi bi-check"></i>
            </button>
            <button class="btn btn-danger btn-sm" onclick="cancelOrder('${orderId}')">
                <i class="bi bi-x"></i>
            </button>
        `;
    } else if (status === 'preparing') {
        buttonsHtml += `
            <button class="btn btn-success btn-sm" onclick="markReady('${orderId}')">
                <i class="bi bi-check"></i>
            </button>
        `;
    } else if (status === 'ready') {
        buttonsHtml += `
            <button class="btn btn-primary btn-sm" onclick="markCompleted('${orderId}')">
                <i class="bi bi-check-all"></i>
            </button>
        `;
    }
    
    // Always add view, edit, and delete buttons
    buttonsHtml += `
        <button class="btn btn-outline-secondary btn-sm" onclick="viewOrder('${orderId}')">
            <i class="bi bi-eye"></i>
        </button>
        <button class="btn btn-outline-primary btn-sm" onclick="printReceipt('${orderId}')">
            <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-outline-danger btn-sm" onclick="deleteOrder('${orderId}')">
            <i class="bi bi-trash"></i>
        </button>
    `;
    
    actionsCell.innerHTML = buttonsHtml;
}
// CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .notification-toast {
        backdrop-filter: blur(10px);
    }
`;
document.head.appendChild(style);
</script>
@endpush