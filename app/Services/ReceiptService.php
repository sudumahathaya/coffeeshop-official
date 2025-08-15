<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate receipt for a completed order
     */
    public function generateReceipt(Order $order, $transactionId = null, $paymentMethod = null)
    {
        try {
            // Calculate loyalty points earned
            $loyaltyPointsEarned = $this->calculateLoyaltyPoints($order->total);
            
            // Generate unique receipt number
            $receiptNumber = $this->generateReceiptNumber();
            
            // Prepare receipt data
            $receiptData = [
                'order_details' => [
                    'order_id' => $order->order_id,
                    'order_type' => $order->order_type,
                    'special_instructions' => $order->special_instructions,
                    'items' => $order->items,
                    'total_items' => $order->total_items
                ],
                'customer_details' => [
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone
                ],
                'payment_details' => [
                    'method' => $paymentMethod ?? $order->payment_method,
                    'transaction_id' => $transactionId,
                    'status' => $order->payment_status
                ],
                'business_details' => [
                    'name' => 'Café Elixir',
                    'address' => 'No.1, Mahamegawaththa Road, Maharagama',
                    'phone' => '+94 77 186 9132',
                    'email' => 'info@cafeelixir.lk',
                    'website' => 'www.cafeelixir.lk',
                    'tax_number' => 'VAT123456789',
                    'business_hours' => '6:00 AM - 10:00 PM'
                ]
            ];

            // Create receipt record
            $receipt = Receipt::create([
                'receipt_number' => $receiptNumber,
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'transaction_id' => $transactionId,
                'payment_method' => $paymentMethod ?? $order->payment_method,
                'payment_status' => $order->payment_status,
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'discount' => $order->discount ?? 0.00,
                'total' => $order->total,
                'loyalty_points_earned' => $loyaltyPointsEarned,
                'receipt_data' => $receiptData,
                'generated_at' => now(),
                'status' => 'active'
            ]);

            Log::info('Receipt generated successfully', [
                'receipt_id' => $receipt->id,
                'receipt_number' => $receiptNumber,
                'order_id' => $order->id,
                'user_id' => $order->user_id
            ]);

            return $receipt;

        } catch (\Exception $e) {
            Log::error('Failed to generate receipt', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'user_id' => $order->user_id
            ]);

            throw $e;
        }
    }

    /**
     * Calculate loyalty points based on order total
     */
    private function calculateLoyaltyPoints($orderTotal)
    {
        // 1 point per Rs. 10 spent
        return (int) ($orderTotal / 10);
    }

    /**
     * Generate unique receipt number
     */
    private function generateReceiptNumber()
    {
        do {
            $receiptNumber = 'RCPT' . date('Ymd') . Str::random(6);
        } while (Receipt::where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }

    /**
     * Get receipt by receipt number
     */
    public function getReceiptByNumber($receiptNumber)
    {
        return Receipt::where('receipt_number', $receiptNumber)
            ->with(['order', 'user'])
            ->first();
    }

    /**
     * Get user's receipt history
     */
    public function getUserReceipts($userId, $limit = 10)
    {
        return Receipt::where('user_id', $userId)
            ->with(['order'])
            ->orderBy('generated_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Cancel receipt
     */
    public function cancelReceipt($receiptId)
    {
        $receipt = Receipt::findOrFail($receiptId);
        $receipt->update(['status' => 'cancelled']);

        Log::info('Receipt cancelled', [
            'receipt_id' => $receiptId,
            'receipt_number' => $receipt->receipt_number
        ]);

        return $receipt;
    }

    /**
     * Generate PDF receipt using DomPDF
     */
    public function generatePDFReceipt(Receipt $receipt)
    {
        try {
            $pdf = \PDF::loadView('receipt.pdf', compact('receipt'));
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'landscape');
            
            // Generate filename
            $filename = 'receipt_' . $receipt->receipt_number . '_' . date('Y-m-d') . '.pdf';
            
            return [
                'pdf' => $pdf,
                'filename' => $filename
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF receipt', [
                'receipt_id' => $receipt->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
}
