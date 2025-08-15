// Simple Receipt Generator for Café Elixir
class ReceiptGenerator {
    constructor() {
        this.init();
    }

    init() {
        console.log('Receipt Generator initialized');
    }

    generateReceipt(orderData, paymentData) {
        const receiptData = {
            ...orderData,
            ...paymentData,
            receiptNumber: this.generateReceiptNumber(),
            timestamp: new Date().toISOString(),
            printedAt: new Date().toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            })
        };

        return this.createReceiptHTML(receiptData);
    }

    generateReceiptNumber() {
        const timestamp = Date.now();
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        return `RCP${timestamp}${random}`;
    }

    createReceiptHTML(data) {
        const subtotal = data.subtotal || 0;
        const tax = data.tax || (subtotal * 0.1);
        const processingFee = data.processing_fee || 0;
        const total = data.total || (subtotal + tax + processingFee);

        return `
            <div class="receipt-container">
                <div class="receipt-header">
                    <div class="cafe-logo">
                        <i class="bi bi-cup-hot-fill"></i>
                    </div>
                    <h2>Café Elixir</h2>
                    <p>No.1, Mahamegawaththa Road<br>Maharagama, Sri Lanka</p>
                    <p>Phone: +94 77 186 9132<br>Email: info@cafeelixir.lk</p>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-info">
                    <div class="info-row">
                        <span>Receipt #:</span>
                        <span>${data.receiptNumber}</span>
                    </div>
                    <div class="info-row">
                        <span>Order ID:</span>
                        <span>${data.order_id || 'N/A'}</span>
                    </div>
                    <div class="info-row">
                        <span>Date & Time:</span>
                        <span>${data.printedAt}</span>
                    </div>
                    <div class="info-row">
                        <span>Customer:</span>
                        <span>${data.customer_name || 'Guest'}</span>
                    </div>
                    ${data.transaction_id && data.transaction_id !== 'CASH_PAYMENT' ? `
                    <div class="info-row">
                        <span>Transaction ID:</span>
                        <span>${data.transaction_id}</span>
                    </div>
                    ` : ''}
                    <div class="info-row">
                        <span>Payment Method:</span>
                        <span>${this.getPaymentMethodName(data.payment_method || 'cash')}</span>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-items">
                    <div class="items-header">
                        <span>Item</span>
                        <span>Qty</span>
                        <span>Price</span>
                        <span>Total</span>
                    </div>
                    ${data.items ? data.items.map(item => `
                        <div class="item-row">
                            <span class="item-name">${item.name}</span>
                            <span class="item-qty">${item.quantity}</span>
                            <span class="item-price">Rs. ${parseFloat(item.price).toFixed(2)}</span>
                            <span class="item-total">Rs. ${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</span>
                        </div>
                    `).join('') : ''}
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>Rs. ${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="total-row">
                        <span>Tax (10%):</span>
                        <span>Rs. ${tax.toFixed(2)}</span>
                    </div>
                    ${processingFee > 0 ? `
                    <div class="total-row">
                        <span>Processing Fee:</span>
                        <span>Rs. ${processingFee.toFixed(2)}</span>
                    </div>
                    ` : ''}
                    <div class="total-row grand-total">
                        <span>TOTAL:</span>
                        <span>Rs. ${total.toFixed(2)}</span>
                    </div>
                </div>

                ${data.points_earned ? `
                <div class="receipt-divider"></div>
                <div class="loyalty-section">
                    <div class="loyalty-earned">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span>Loyalty Points Earned: <strong>${data.points_earned}</strong></span>
                    </div>
                </div>
                ` : ''}

                <div class="receipt-divider"></div>

                <div class="receipt-footer">
                    <p><strong>Thank you for visiting Café Elixir!</strong></p>
                    <p>We hope you enjoyed your coffee experience.</p>
                    <p class="small">Follow us on social media @cafeelixir</p>
                    <p class="small">Visit us again soon!</p>
                </div>

                <div class="receipt-qr">
                    <div class="qr-placeholder">
                        <i class="bi bi-qr-code"></i>
                        <small>Scan for feedback</small>
                    </div>
                </div>
            </div>
        `;
    }

    getPaymentMethodName(method) {
        const methods = {
            'card': 'Credit/Debit Card',
            'mobile': 'Mobile Payment',
            'bank_transfer': 'Bank Transfer',
            'digital_wallet': 'Digital Wallet',
            'cash': 'Cash Payment'
        };
        return methods[method] || method;
    }

    showReceiptModal(orderData, paymentData) {
        const receiptHTML = this.generateReceipt(orderData, paymentData);
        
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-coffee text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-receipt me-2"></i>Payment Receipt
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="receipt-wrapper">
                            ${receiptHTML}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-coffee" onclick="printReceipt()">
                            <i class="bi bi-printer me-2"></i>Print Receipt
                        </button>
                        <button type="button" class="btn btn-coffee" onclick="downloadReceipt()">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        // Store receipt data globally for print/download
        window.currentReceiptData = { orderData, paymentData };

        // Clean up when modal is hidden
        modal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modal);
            delete window.currentReceiptData;
        });

        return modal;
    }

    printReceipt() {
        const receiptContent = document.querySelector('.receipt-container').outerHTML;
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Receipt - Café Elixir</title>
                <style>
                    ${this.getReceiptStyles()}
                    @media print {
                        body { margin: 0; }
                        .receipt-container { 
                            box-shadow: none; 
                            border: none;
                            max-width: none;
                            margin: 0;
                        }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${receiptContent}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    }

    downloadReceipt() {
        // Simple text-based receipt for download
        const data = window.currentReceiptData;
        if (!data) return;

        const receiptText = this.generateTextReceipt(data.orderData, data.paymentData);
        
        const blob = new Blob([receiptText], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `cafe-elixir-receipt-${Date.now()}.txt`;
        a.click();
        URL.revokeObjectURL(url);

        this.showNotification('Receipt downloaded successfully!', 'success');
    }

    generateTextReceipt(orderData, paymentData) {
        const subtotal = orderData.subtotal || 0;
        const tax = orderData.tax || (subtotal * 0.1);
        const processingFee = paymentData.processing_fee || 0;
        const total = orderData.total || (subtotal + tax + processingFee);
        const timestamp = new Date().toLocaleString();

        let receipt = `
=====================================
           CAFÉ ELIXIR
=====================================
No.1, Mahamegawaththa Road
Maharagama, Sri Lanka
Phone: +94 77 186 9132
Email: info@cafeelixir.lk

=====================================
Receipt #: RCP${Date.now()}
Order ID: ${orderData.order_id || 'N/A'}
Date: ${timestamp}
Customer: ${orderData.customer_name || 'Guest'}
${paymentData.transaction_id && paymentData.transaction_id !== 'CASH_PAYMENT' ? `Transaction: ${paymentData.transaction_id}` : ''}
Payment: ${this.getPaymentMethodName(orderData.payment_method || 'cash')}

=====================================
ITEMS ORDERED:
=====================================
`;

        if (orderData.items) {
            orderData.items.forEach(item => {
                const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
                receipt += `${item.name.padEnd(20)} x${item.quantity.toString().padStart(2)} Rs. ${itemTotal.toFixed(2).padStart(8)}\n`;
            });
        }

        receipt += `
=====================================
PAYMENT SUMMARY:
=====================================
Subtotal:        Rs. ${subtotal.toFixed(2).padStart(8)}
Tax (10%):       Rs. ${tax.toFixed(2).padStart(8)}`;

        if (processingFee > 0) {
            receipt += `\nProcessing Fee:  Rs. ${processingFee.toFixed(2).padStart(8)}`;
        }

        receipt += `
-------------------------------------
TOTAL:           Rs. ${total.toFixed(2).padStart(8)}
=====================================
`;

        if (paymentData.points_earned) {
            receipt += `
Loyalty Points Earned: ${paymentData.points_earned}
`;
        }

        receipt += `
Thank you for visiting Café Elixir!
We hope you enjoyed your experience.

Follow us: @cafeelixir
Visit us again soon!

=====================================
        `;

        return receipt;
    }

    getReceiptStyles() {
        return `
            body {
                font-family: 'Courier New', monospace;
                margin: 20px;
                background: #f5f5f5;
            }

            .receipt-container {
                background: white;
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border: 2px dashed #8B4513;
            }

            .receipt-header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #8B4513;
                padding-bottom: 15px;
            }

            .cafe-logo {
                font-size: 3rem;
                color: #8B4513;
                margin-bottom: 10px;
            }

            .receipt-header h2 {
                color: #8B4513;
                font-weight: bold;
                margin: 10px 0;
                font-size: 1.8rem;
            }

            .receipt-header p {
                margin: 5px 0;
                color: #666;
                font-size: 0.9rem;
            }

            .receipt-divider {
                border-top: 1px dashed #8B4513;
                margin: 15px 0;
            }

            .receipt-info {
                margin-bottom: 15px;
            }

            .info-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                font-size: 0.9rem;
            }

            .info-row span:first-child {
                font-weight: bold;
                color: #333;
            }

            .info-row span:last-child {
                color: #666;
            }

            .receipt-items {
                margin-bottom: 15px;
            }

            .items-header {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1fr;
                gap: 10px;
                font-weight: bold;
                color: #8B4513;
                border-bottom: 1px solid #8B4513;
                padding-bottom: 8px;
                margin-bottom: 10px;
                font-size: 0.85rem;
            }

            .item-row {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1fr;
                gap: 10px;
                margin-bottom: 8px;
                font-size: 0.85rem;
                align-items: center;
            }

            .item-name {
                font-weight: 500;
                color: #333;
            }

            .item-qty, .item-price, .item-total {
                text-align: right;
                color: #666;
            }

            .receipt-totals {
                margin-bottom: 15px;
            }

            .total-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                font-size: 0.9rem;
            }

            .total-row span:first-child {
                color: #333;
            }

            .total-row span:last-child {
                color: #666;
                font-weight: 500;
            }

            .grand-total {
                border-top: 2px solid #8B4513;
                padding-top: 8px;
                font-weight: bold;
                font-size: 1.1rem;
            }

            .grand-total span {
                color: #8B4513 !important;
                font-weight: bold;
            }

            .loyalty-section {
                text-align: center;
                background: linear-gradient(45deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.2));
                padding: 10px;
                border-radius: 8px;
                border: 1px solid #ffc107;
                margin-bottom: 15px;
            }

            .loyalty-earned {
                color: #856404;
                font-weight: bold;
            }

            .receipt-footer {
                text-align: center;
                color: #666;
                font-size: 0.85rem;
                line-height: 1.4;
            }

            .receipt-footer p {
                margin: 8px 0;
            }

            .receipt-footer .small {
                font-size: 0.75rem;
                color: #999;
            }

            .receipt-qr {
                text-align: center;
                margin-top: 20px;
                padding-top: 15px;
                border-top: 1px dashed #8B4513;
            }

            .qr-placeholder {
                color: #8B4513;
                font-size: 2rem;
            }

            .qr-placeholder small {
                display: block;
                font-size: 0.7rem;
                margin-top: 5px;
                color: #666;
            }

            @media (max-width: 576px) {
                .receipt-container {
                    margin: 10px;
                    padding: 15px;
                }

                .items-header, .item-row {
                    grid-template-columns: 2fr 0.8fr 1fr 1fr;
                    gap: 5px;
                    font-size: 0.8rem;
                }

                .receipt-header h2 {
                    font-size: 1.5rem;
                }

                .cafe-logo {
                    font-size: 2.5rem;
                }
            }
        `;
    }

    showNotification(message, type = 'info') {
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

        const iconMap = {
            'success': 'check-circle-fill',
            'error': 'exclamation-triangle-fill',
            'warning': 'exclamation-triangle-fill',
            'info': 'info-circle-fill'
        };

        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${iconMap[type]} me-2"></i>
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
        }, 4000);
    }
}

// Initialize receipt generator
document.addEventListener('DOMContentLoaded', function() {
    window.receiptGenerator = new ReceiptGenerator();
});

// Global functions for receipt actions
window.printReceipt = function() {
    if (window.receiptGenerator) {
        window.receiptGenerator.printReceipt();
    }
};

window.downloadReceipt = function() {
    if (window.receiptGenerator) {
        window.receiptGenerator.downloadReceipt();
    }
};

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

    .receipt-wrapper {
        max-height: 70vh;
        overflow-y: auto;
        padding: 20px;
    }

    .receipt-container {
        background: white;
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 10px;
        border: 2px dashed #8B4513;
        font-family: 'Courier New', monospace;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #8B4513;
        padding-bottom: 15px;
    }

    .cafe-logo {
        font-size: 3rem;
        color: #8B4513;
        margin-bottom: 10px;
    }

    .receipt-header h2 {
        color: #8B4513;
        font-weight: bold;
        margin: 10px 0;
        font-size: 1.8rem;
    }

    .receipt-header p {
        margin: 5px 0;
        color: #666;
        font-size: 0.9rem;
    }

    .receipt-divider {
        border-top: 1px dashed #8B4513;
        margin: 15px 0;
    }

    .receipt-info {
        margin-bottom: 15px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .info-row span:first-child {
        font-weight: bold;
        color: #333;
    }

    .info-row span:last-child {
        color: #666;
    }

    .receipt-items {
        margin-bottom: 15px;
    }

    .items-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 10px;
        font-weight: bold;
        color: #8B4513;
        border-bottom: 1px solid #8B4513;
        padding-bottom: 8px;
        margin-bottom: 10px;
        font-size: 0.85rem;
    }

    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 0.85rem;
        align-items: center;
    }

    .item-name {
        font-weight: 500;
        color: #333;
    }

    .item-qty, .item-price, .item-total {
        text-align: right;
        color: #666;
    }

    .receipt-totals {
        margin-bottom: 15px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .total-row span:first-child {
        color: #333;
    }

    .total-row span:last-child {
        color: #666;
        font-weight: 500;
    }

    .grand-total {
        border-top: 2px solid #8B4513;
        padding-top: 8px;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .grand-total span {
        color: #8B4513 !important;
        font-weight: bold;
    }

    .loyalty-section {
        text-align: center;
        background: linear-gradient(45deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.2));
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ffc107;
        margin-bottom: 15px;
    }

    .loyalty-earned {
        color: #856404;
        font-weight: bold;
    }

    .receipt-footer {
        text-align: center;
        color: #666;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .receipt-footer p {
        margin: 8px 0;
    }

    .receipt-footer .small {
        font-size: 0.75rem;
        color: #999;
    }

    .receipt-qr {
        text-align: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px dashed #8B4513;
    }

    .qr-placeholder {
        color: #8B4513;
        font-size: 2rem;
    }

    .qr-placeholder small {
        display: block;
        font-size: 0.7rem;
        margin-top: 5px;
        color: #666;
    }
`;
    }
}

// Initialize receipt generator
document.addEventListener('DOMContentLoaded', function() {
    window.receiptGenerator = new ReceiptGenerator();
});