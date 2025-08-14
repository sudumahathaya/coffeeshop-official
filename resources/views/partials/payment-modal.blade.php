<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-coffee text-white">
                <h5 class="modal-title">
                    <i class="bi bi-credit-card me-2"></i>Complete Your Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Order Summary -->
                    <div class="col-lg-5 order-2 order-lg-1">
                        <div class="order-summary">
                            <h6 class="mb-3">
                                <i class="bi bi-receipt me-2"></i>Order Summary
                            </h6>
                            <div id="orderSummaryItems">
                                <!-- Order items will be populated here -->
                            </div>
                            <hr>
                            <div class="summary-totals">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="summarySubtotal">Rs. 0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax (10%):</span>
                                    <span id="summaryTax">Rs. 0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Processing Fee:</span>
                                    <span id="summaryProcessingFee">Rs. 0.00</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold h5 text-coffee">
                                    <span>Total:</span>
                                    <span id="summaryTotal">Rs. 0.00</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="col-lg-7 order-1 order-lg-2">
                        <form id="paymentForm" class="payment-form">
                            @csrf
                            <input type="hidden" id="paymentOrderId" name="order_id">
                            <input type="hidden" id="paymentAmount" name="amount">
                            <input type="hidden" id="paymentCurrency" name="currency" value="LKR">
                            <input type="hidden" id="paymentCustomerName" name="customer_name">
                            <input type="hidden" id="paymentCustomerEmail" name="customer_email">
                            <input type="hidden" id="paymentCustomerPhone" name="customer_phone">

                            <!-- Payment Method Selection -->
                            <div class="mb-4">
                                <h6 class="mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Payment Method
                                </h6>
                                <div class="payment-methods">
                                    <div class="row g-2">
                                        <div class="col-6 col-md-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="method_card" value="card" checked>
                                            <label class="btn btn-outline-primary w-100 py-2 py-md-3" for="method_card">
                                                <i class="bi bi-credit-card d-block mb-1 mb-md-2" style="font-size: 1.5rem;"></i>
                                                <strong>Credit/Debit Card</strong><br>
                                                <small class="d-none d-md-block">Visa, Mastercard, Amex</small>
                                            </label>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="method_mobile" value="mobile">
                                            <label class="btn btn-outline-success w-100 py-2 py-md-3" for="method_mobile">
                                                <i class="bi bi-phone d-block mb-1 mb-md-2" style="font-size: 1.5rem;"></i>
                                                <strong>Mobile Payment</strong><br>
                                                <small class="d-none d-md-block">Dialog, Mobitel, Hutch</small>
                                            </label>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="method_bank" value="bank_transfer">
                                            <label class="btn btn-outline-info w-100 py-2 py-md-3" for="method_bank">
                                                <i class="bi bi-bank d-block mb-1 mb-md-2" style="font-size: 1.5rem;"></i>
                                                <strong>Bank Transfer</strong><br>
                                                <small class="d-none d-md-block">All major banks</small>
                                            </label>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <input type="radio" class="btn-check" name="payment_method" id="method_cash" value="cash">
                                            <label class="btn btn-outline-warning w-100 py-2 py-md-3" for="method_cash">
                                                <i class="bi bi-cash-stack d-block mb-1 mb-md-2" style="font-size: 1.5rem;"></i>
                                                <strong>Cash Payment</strong><br>
                                                <small class="d-none d-md-block">Pay at café</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details Container -->
                            <div id="paymentDetails">
                                <!-- Payment method specific forms will be loaded here -->
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-coffee btn-lg">
                                    <i class="bi bi-lock me-2"></i>
                                    <span class="btn-text">Complete Payment</span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Processing...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .order-summary {
        margin-bottom: 2rem;
    }
    
    .payment-methods .btn {
        font-size: 0.8rem;
        padding: 0.5rem !important;
    }
    
    .payment-methods .btn strong {
        font-size: 0.75rem;
    }
    
    .payment-methods .btn i {
        font-size: 1.2rem !important;
    }
}

@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.25rem;
    }
    
    .modal-xl {
        max-width: 100%;
    }
    
    .payment-methods .row {
        gap: 0.5rem;
    }
    
    .payment-methods .col-6 {
        flex: 0 0 calc(50% - 0.25rem);
    }
}

.payment-methods .btn-check:checked + .btn {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.payment-methods .btn-outline-primary.btn-check:checked + .btn {
    background-color: #0d6efd;
}

.payment-methods .btn-outline-success.btn-check:checked + .btn {
    background-color: #198754;
}

.payment-methods .btn-outline-info.btn-check:checked + .btn {
    background-color: #0dcaf0;
}

.payment-methods .btn-outline-warning.btn-check:checked + .btn {
    background-color: #ffc107;
    color: #000;
}

.order-summary {
    background: linear-gradient(45deg, rgba(139, 69, 19, 0.05), rgba(210, 105, 30, 0.05));
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(139, 69, 19, 0.1);
    height: fit-content;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(139, 69, 19, 0.1);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
}

.summary-item-details {
    flex-grow: 1;
}

.summary-item-details h6 {
    margin-bottom: 0.25rem;
    color: var(--coffee-primary);
}

.summary-item-price {
    font-weight: 600;
    color: var(--coffee-primary);
}

.bg-coffee {
    background: linear-gradient(45deg, var(--coffee-primary), var(--coffee-secondary)) !important;
}

.btn-coffee {
    background: linear-gradient(45deg, var(--coffee-primary), var(--coffee-secondary));
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.btn-coffee:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
    color: white;
}

.btn-coffee.loading .btn-text {
    display: none;
}

.btn-coffee.loading .btn-loading {
    display: inline-block !important;
}

#paymentDetails {
    min-height: 200px;
    transition: all 0.3s ease;
}

.card-payment-form,
.mobile-payment-form,
.bank-transfer-form,
.digital-wallet-form,
.cash-payment-form {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    border: 1px solid rgba(139, 69, 19, 0.1);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--coffee-primary);
    box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
}

.card-types img,
.provider-logos img {
    margin-right: 0.5rem;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.card-types img:hover,
.provider-logos img:hover {
    opacity: 1;
}
</style>

<script>
// Global function to show payment modal
function showPaymentModal(orderData) {
    if (!orderData) {
        console.error('No order data provided');
        showNotification('Order data not found. Please try again.', 'error');
        return;
    }

    console.log('Opening payment modal with data:', orderData);

    // Populate order summary
    populateOrderSummary(orderData);

    // Populate hidden form fields
    document.getElementById('paymentOrderId').value = orderData.order_id || '';
    document.getElementById('paymentAmount').value = orderData.total || 0;
    document.getElementById('paymentCustomerName').value = orderData.customer_name || '';
    document.getElementById('paymentCustomerEmail').value = orderData.customer_email || '';
    document.getElementById('paymentCustomerPhone').value = orderData.customer_phone || '';

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();

    // Initialize payment method after modal is shown
    modal._element.addEventListener('shown.bs.modal', function() {
        // Set default payment method to card
        const cardRadio = document.getElementById('method_card');
        if (cardRadio) {
            cardRadio.checked = true;
            handlePaymentMethodChange('card');
        }
    }, { once: true });
}

function populateOrderSummary(orderData) {
    console.log('Populating order summary with:', orderData);

    const itemsContainer = document.getElementById('orderSummaryItems');
    const subtotalElement = document.getElementById('summarySubtotal');
    const taxElement = document.getElementById('summaryTax');
    const totalElement = document.getElementById('summaryTotal');
    const amountInput = document.getElementById('paymentAmount');

    if (!itemsContainer) {
        console.error('Order summary container not found');
        return;
    }

    // Populate items
    if (orderData.items && orderData.items.length > 0) {
        itemsContainer.innerHTML = orderData.items.map(item => `
            <div class="summary-item">
                <img src="${item.image || 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=50&h=50&fit=crop'}"
                     class="summary-item-image" alt="${item.name}">
                <div class="summary-item-details">
                    <h6>${item.name}</h6>
                    <small class="text-muted">Rs. ${parseFloat(item.price).toFixed(2)} × ${item.quantity}</small>
                </div>
                <div class="summary-item-price">
                    Rs. ${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}
                </div>
            </div>
        `).join('');
    } else {
        itemsContainer.innerHTML = '<p class="text-muted">No items in order</p>';
    }

    // Update totals
    const subtotal = orderData.subtotal || orderData.total || 0;
    const tax = orderData.tax || (subtotal * 0.1);
    const processingFee = calculateProcessingFee(subtotal, 'card'); // Default to card fee
    const total = orderData.total || (subtotal + tax + processingFee);

    if (subtotalElement) subtotalElement.textContent = `Rs. ${subtotal.toFixed(2)}`;
    if (taxElement) taxElement.textContent = `Rs. ${tax.toFixed(2)}`;
    if (document.getElementById('summaryProcessingFee')) document.getElementById('summaryProcessingFee').textContent = `Rs. ${processingFee.toFixed(2)}`;
    if (totalElement) totalElement.textContent = `Rs. ${total.toFixed(2)}`;
    if (amountInput) amountInput.value = total;

    console.log('Order summary populated successfully');
}

function calculateProcessingFee(amount, method) {
    const fees = {
        'card': { percentage: 2.9, fixed: 30 },
        'mobile': { percentage: 1.5, fixed: 10 },
        'bank_transfer': { percentage: 0.5, fixed: 25 },
        'digital_wallet': { percentage: 2.0, fixed: 15 },
        'cash': { percentage: 0, fixed: 0 }
    };

    const fee = fees[method] || fees['card'];
    return Math.round(((amount * fee.percentage / 100) + fee.fixed) * 100) / 100;
}

function handlePaymentMethodChange(method) {
    const paymentDetails = document.getElementById('paymentDetails');
    if (!paymentDetails) return;

    let detailsHTML = '';

    switch (method) {
        case 'card':
            detailsHTML = renderCardForm();
            break;
        case 'mobile':
            detailsHTML = renderMobileForm();
            break;
        case 'bank_transfer':
            detailsHTML = renderBankTransferForm();
            break;
        case 'digital_wallet':
            detailsHTML = renderDigitalWalletForm();
            break;
        case 'cash':
            detailsHTML = renderCashForm();
            break;
    }

    paymentDetails.innerHTML = detailsHTML;

    // Update processing fee based on selected method
    const currentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
    const orderData = window.currentOrderData;
    if (orderData) {
        const subtotal = orderData.subtotal || 0;
        const tax = orderData.tax || (subtotal * 0.1);
        const processingFee = calculateProcessingFee(subtotal, method);
        const total = subtotal + tax + processingFee;

        document.getElementById('summaryProcessingFee').textContent = `Rs. ${processingFee.toFixed(2)}`;
        document.getElementById('summaryTotal').textContent = `Rs. ${total.toFixed(2)}`;
        document.getElementById('paymentAmount').value = total;
    }

    // Add animations
    paymentDetails.style.opacity = '0';
    paymentDetails.style.transform = 'translateY(20px)';

    setTimeout(() => {
        paymentDetails.style.transition = 'all 0.3s ease';
        paymentDetails.style.opacity = '1';
        paymentDetails.style.transform = 'translateY(0)';
    }, 100);
}

function renderCardForm() {
    return `
        <div class="card-payment-form">
            <h6 class="mb-3"><i class="bi bi-credit-card me-2"></i>Card Details</h6>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Card Number *</label>
                    <input type="text" class="form-control form-control-lg" name="card_number"
                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                    <div class="card-types mt-2">
                        <img src="https://img.icons8.com/color/24/visa.png" alt="Visa" title="Visa">
                        <img src="https://img.icons8.com/color/24/mastercard.png" alt="Mastercard" title="Mastercard">
                        <img src="https://img.icons8.com/color/24/amex.png" alt="Amex" title="American Express">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Expiry Date *</label>
                    <input type="text" class="form-control form-control-lg" name="card_expiry"
                           placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">CVC *</label>
                    <input type="text" class="form-control form-control-lg" name="card_cvc"
                           placeholder="123" maxlength="4" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Cardholder Name *</label>
                    <input type="text" class="form-control form-control-lg" name="card_holder"
                           placeholder="John Doe" required>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Payment Info:</strong> Enter any valid card details for successful payment.
                Use 4000000000000002 to test declined payments.
            </div>
        </div>
    `;
}

function renderMobileForm() {
    return `
        <div class="mobile-payment-form">
            <h6 class="mb-3"><i class="bi bi-phone me-2"></i>Mobile Payment</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mobile Provider *</label>
                    <select class="form-select form-select-lg" name="mobile_provider" required>
                        <option value="">Select Provider</option>
                        <option value="dialog">Dialog</option>
                        <option value="mobitel">Mobitel</option>
                        <option value="hutch">Hutch</option>
                        <option value="airtel">Airtel</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mobile Number *</label>
                    <input type="tel" class="form-control form-control-lg" name="mobile_number"
                           placeholder="077 123 4567" required>
                </div>
            </div>

            <div class="alert alert-success mt-3">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Secure Payment:</strong> You'll receive an OTP to confirm the payment.<br>
                <small class="text-muted">For testing, use any valid Sri Lankan mobile number</small>
            </div>

            <div class="provider-logos mt-3">
                <img src="https://img.icons8.com/color/32/dialog.png" alt="Dialog" title="Dialog">
                <img src="https://img.icons8.com/color/32/mobitel.png" alt="Mobitel" title="Mobitel">
                <img src="https://img.icons8.com/color/32/hutch.png" alt="Hutch" title="Hutch">
            </div>
        </div>
    `;
}

function renderBankTransferForm() {
    return `
        <div class="bank-transfer-form">
            <h6 class="mb-3"><i class="bi bi-bank me-2"></i>Bank Transfer</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Bank *</label>
                    <select class="form-select form-select-lg" name="bank_code" required>
                        <option value="">Select Bank</option>
                        <option value="BOC">Bank of Ceylon</option>
                        <option value="PB">People's Bank</option>
                        <option value="CB">Commercial Bank</option>
                        <option value="HNB">Hatton National Bank</option>
                        <option value="DFCC">DFCC Bank</option>
                        <option value="NSB">National Savings Bank</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Account Number *</label>
                    <input type="text" class="form-control form-control-lg" name="account_number"
                           placeholder="1234567890" required>
                </div>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="bi bi-clock me-2"></i>
                <strong>Processing Time:</strong> Bank transfers may take 1-3 business days to process.
            </div>
        </div>
    `;
}

function renderDigitalWalletForm() {
    return `
        <div class="digital-wallet-form">
            <h6 class="mb-3"><i class="bi bi-wallet2 me-2"></i>Digital Wallet</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Wallet Type *</label>
                    <select class="form-select form-select-lg" name="wallet_type" required>
                        <option value="">Select Wallet</option>
                        <option value="paypal">PayPal</option>
                        <option value="google_pay">Google Pay</option>
                        <option value="apple_pay">Apple Pay</option>
                        <option value="samsung_pay">Samsung Pay</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Wallet ID/Email *</label>
                    <input type="text" class="form-control form-control-lg" name="wallet_id"
                           placeholder="user@example.com" required>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="bi bi-shield-check me-2"></i>
                <strong>Secure:</strong> You'll be redirected to your wallet provider for authentication.
            </div>
        </div>
    `;
}

function renderCashForm() {
    return `
        <div class="cash-payment-form">
            <div class="alert alert-success">
                <i class="bi bi-cash-stack me-2"></i>
                <strong>Cash Payment Selected</strong><br>
                You can pay with cash when you collect your order or at your table.
            </div>

            <div class="payment-instructions">
                <h6>Payment Instructions:</h6>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check text-success me-2"></i>Order will be confirmed immediately</li>
                    <li><i class="bi bi-check text-success me-2"></i>Pay when you arrive at the café</li>
                    <li><i class="bi bi-check text-success me-2"></i>Show your order ID to our staff</li>
                    <li><i class="bi bi-check text-success me-2"></i>Exact change appreciated</li>
                </ul>
            </div>
        </div>
    `;
}

// Global function to submit order
async function submitOrder(orderData) {
    try {
        // Set flags to manage cart clearing properly during payment
        window.paymentInProgress = true;
        window.orderSuccessful = false;
        window.checkoutInProgress = false; // Reset checkout flag

        console.log('Submitting order:', orderData);

        // Validate orderData before submission
        if (!orderData || !orderData.items || orderData.items.length === 0) {
            throw new Error('Invalid order data: No items found');
        }

        if (!orderData.customer_name) {
            throw new Error('Customer name is required');
        }

        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('HTTP Error:', response.status, errorText);
            throw new Error(`Server error: ${response.status}`);
        }

        const result = await response.json();
        console.log('Order submission result:', result);

        if (result.success) {
            console.log('Order submitted successfully:', result);

            // Set success flag
            window.orderSuccessful = true;

            // Clear cart only after successful order submission
            if (typeof window.cart !== 'undefined') {
                window.cart.clearCart();
                console.log('Cart cleared via cart object');
            } else if (localStorage.getItem('cafeElixirCart')) {
                localStorage.removeItem('cafeElixirCart');
                console.log('Cart cleared via localStorage');

                // Update cart counters
                const cartCounters = document.querySelectorAll('.cart-counter');
                cartCounters.forEach(counter => {
                    counter.style.display = 'none';
                    counter.textContent = '0';
                });
            }

            // Close payment modal
            const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            if (paymentModal) {
                paymentModal.hide();
            }

            // Show success notification
            const pointsMessage = result.points_earned ? ` 🌟 You earned ${result.points_earned} loyalty points!` : '';
            showNotification(`Order ${result.order_id || 'placed'} successful!${pointsMessage} ☕`, 'success');

            // Enhanced redirect logic with celebration
            setTimeout(() => {
                if (typeof window.location !== 'undefined') {
                    if (window.location.pathname !== '/user/dashboard') {
                        window.location.href = '/user/dashboard?order_success=true&points=' + (result.points_earned || 0);
                    } else {
                        // If already on dashboard, refresh to show updated stats
                        window.location.reload();
                    }
                }
            }, 2000);
        } else {
            console.error('Order submission failed:', result);
            throw new Error(result.message || 'Order submission failed. Please try again.');
        }
    } catch (error) {
        console.error('Order submission error:', error);
        showNotification(error.message || 'Failed to place order. Please try again.', 'error');
        throw error; // Re-throw to be handled by payment system
    } finally {
        // Reset flags
        window.paymentInProgress = false;
        window.checkoutInProgress = false;
        setTimeout(() => {
            window.orderSuccessful = false;
        }, 5000);
    }
}

// Initialize payment modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize payment state flags
    window.paymentInProgress = false;
    window.orderSuccessful = false;
    window.checkoutInProgress = false;

    // Payment method change handlers
    document.addEventListener('change', function(e) {
        if (e.target.name === 'payment_method') {
            handlePaymentMethodChange(e.target.value);
        }
    });

    // Initialize with card form by default
    setTimeout(() => {
        handlePaymentMethodChange('card');
    }, 500);

    // Form submission handler
    document.addEventListener('submit', function(e) {
        if (e.target.id === 'paymentForm') {
            e.preventDefault();

            const submitButton = e.target.querySelector('button[type="submit"]');
            submitButton.classList.add('loading');
            submitButton.disabled = true;

            // Handle payment processing
                    // Refresh dashboard stats without full page reload
                    if (typeof refreshDashboardStats === 'function') {
                        refreshDashboardStats();
                    } else {
                        window.location.reload();
                    }
                window.cafeElixirPaymentSystem.handlePaymentSubmission(e.target);
            } else {
                console.error('Payment system not initialized');
                showNotification('Payment system is loading. Please try again in a moment.', 'warning');
                submitButton.classList.remove('loading');
                submitButton.disabled = false;
                // Reset checkout flag on error
                window.checkoutInProgress = false;
            }
        }
    });

    // Input formatting
    document.addEventListener('input', function(e) {
        if (e.target.name === 'card_number') {
            formatCardNumber(e.target);
        } else if (e.target.name === 'card_expiry') {
            formatExpiryDate(e.target);
        } else if (e.target.name === 'mobile_number') {
            formatPhoneNumber(e.target);
        }
    });

    // Handle payment modal close events
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('hide.bs.modal', function() {
            // Only reset flags if order was not successful
            if (!window.orderSuccessful) {
                window.paymentInProgress = false;
                window.checkoutInProgress = false;
            }
        });

        paymentModal.addEventListener('hidden.bs.modal', function() {
            // Reset all flags when modal is completely hidden
            setTimeout(() => {
                if (!window.orderSuccessful) {
                    window.paymentInProgress = false;
                    window.checkoutInProgress = false;
                }
            }, 100);
        });
    }
});

// Input formatting functions
function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
    input.value = value;
}

function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');

    if (value.startsWith('94')) {
        value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 4) + ' ' +
                value.substring(4, 7) + ' ' + value.substring(7, 11);
    } else if (value.startsWith('0')) {
        value = value.substring(1, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6, 10);
    }

    input.value = value;
}
</script>
