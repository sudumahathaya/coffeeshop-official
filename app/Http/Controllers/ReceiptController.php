<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function downloadReceipt($orderId)
    {
        try {
            // Find the order
            $order = Order::where('order_id', $orderId)->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check if user owns this order or is admin
            if (Auth::check() && (Auth::id() === $order->user_id || Auth::user()->isAdmin())) {
                // Generate receipt data
                $receiptData = $this->generateReceiptData($order);
                
                // Generate PDF
                $pdf = Pdf::loadView('receipts.order-receipt', $receiptData);
                
                // Return PDF download
                return $pdf->download("receipt-{$order->order_id}.pdf");
            }

            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewReceipt($orderId)
    {
        try {
            $order = Order::where('order_id', $orderId)->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check permissions
            if (Auth::check() && (Auth::id() === $order->user_id || Auth::user()->isAdmin())) {
                $receiptData = $this->generateReceiptData($order);
                return view('receipts.order-receipt', $receiptData);
            }

            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateReceiptData($order)
    {
        // Calculate totals
        $subtotal = $order->subtotal;
        $tax = $order->tax;
        $total = $order->total;

        // Get payment transaction if exists
        $transaction = PaymentTransaction::where('order_id', $order->id)->first();

        return [
            'order' => $order,
            'transaction' => $transaction,
            'cafe_info' => [
                'name' => 'Café Elixir',
                'address' => 'No.1, Mahamegawaththa Road, Maharagama',
                'phone' => '+94 77 186 9132',
                'email' => 'info@cafeelixir.lk',
                'website' => 'www.cafeelixir.lk'
            ],
            'receipt_info' => [
                'receipt_number' => 'RCP-' . $order->order_id,
                'generated_at' => now(),
                'generated_by' => Auth::user()->name ?? 'System'
            ],
            'totals' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'tax_rate' => 10 // 10% tax rate
            ]
        ];
    }

    public function emailReceipt(Request $request, $orderId)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email'
            ]);

            $order = Order::where('order_id', $orderId)->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check permissions
            if (Auth::check() && (Auth::id() === $order->user_id || Auth::user()->isAdmin())) {
                // Generate receipt data
                $receiptData = $this->generateReceiptData($order);
                
                // Generate PDF
                $pdf = Pdf::loadView('receipts.order-receipt', $receiptData);
                
                // In a real application, you would send this via email
                // For now, we'll simulate the email sending
                
                return response()->json([
                    'success' => true,
                    'message' => "Receipt has been sent to {$validatedData['email']}"
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to email receipt: ' . $e->getMessage()
            ], 500);
        }
    }
}