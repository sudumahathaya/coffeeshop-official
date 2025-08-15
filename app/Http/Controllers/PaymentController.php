<?php

namespace App\Http\Controllers;

use App\Services\SimulationPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(SimulationPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment intent
     */
    public function createIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|in:LKR,USD',
            'method' => 'nullable|string|in:card,mobile,bank_transfer,digital_wallet',
            'order_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment data',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->paymentService->createPaymentIntent(
            $request->amount,
            $request->currency ?? 'LKR',
            $request->method ?? 'card'
        );

        return response()->json($result);
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string|in:card,mobile,bank_transfer,digital_wallet',
            'currency' => 'nullable|string|in:LKR,USD',
            'order_id' => 'nullable|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            
            // Card specific fields
            'card_number' => 'required_if:method,card|nullable|string',
            'card_expiry' => 'required_if:method,card|nullable|string',
            'card_cvc' => 'required_if:method,card|nullable|string',
            'card_holder' => 'required_if:method,card|nullable|string',
            
            // Mobile payment specific fields
            'mobile_provider' => 'required_if:method,mobile|nullable|string|in:dialog,mobitel,hutch,airtel',
            'mobile_number' => 'required_if:method,mobile|nullable|string',
            
            // Bank transfer specific fields
            'bank_code' => 'required_if:method,bank_transfer|nullable|string',
            'account_number' => 'required_if:method,bank_transfer|nullable|string',
            
            // Digital wallet specific fields
            'wallet_type' => 'required_if:method,digital_wallet|nullable|string',
            'wallet_id' => 'required_if:method,digital_wallet|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment data',
                'errors' => $validator->errors()
            ], 422);
        }

        $paymentData = [
            'amount' => $request->amount,
            'method' => $request->method,
            'currency' => $request->currency ?? 'LKR',
            'order_id' => $request->order_id,
            'customer' => [
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone
            ]
        ];

        // Add method-specific data
        switch ($request->method) {
            case 'card':
                $paymentData['card'] = [
                    'number' => $this->maskCardNumber($request->card_number),
                    'expiry' => $request->card_expiry,
                    'holder' => $request->card_holder
                ];
                break;

            case 'mobile':
                $paymentData['mobile'] = [
                    'provider' => $request->mobile_provider,
                    'number' => $request->mobile_number
                ];
                break;

            case 'bank_transfer':
                $paymentData['bank'] = [
                    'code' => $request->bank_code,
                    'account' => $this->maskAccountNumber($request->account_number)
                ];
                break;

            case 'digital_wallet':
                $paymentData['wallet'] = [
                    'type' => $request->wallet_type,
                    'id' => $request->wallet_id
                ];
                break;
        }

        $result = $this->paymentService->processPayment($paymentData);

        // If payment is successful, generate receipt
        if ($result['success'] && isset($result['transaction_id'])) {
            Log::info('Payment successful, attempting receipt generation', [
                'result' => $result,
                'order_id' => $request->order_id,
                'method' => $request->method
            ]);
            
            try {
                // Find the order if order_id is provided
                if ($request->order_id) {
                    $order = \App\Models\Order::where('order_id', $request->order_id)->first();
                    if ($order) {
                        Log::info('Found existing order for receipt generation', ['order_id' => $order->id]);
                        $receiptService = app(\App\Services\ReceiptService::class);
                        $receipt = $receiptService->generateReceipt(
                            $order, 
                            $result['transaction_id'], 
                            $request->method
                        );
                        
                        // Add receipt information to the response
                        $result['receipt'] = [
                            'receipt_number' => $receipt->receipt_number,
                            'receipt_url' => route('receipt.show', $receipt->receipt_number) . '?new_receipt=true'
                        ];
                        
                        // Log successful receipt generation
                        Log::info('Receipt generated after payment', [
                            'receipt_number' => $receipt->receipt_number,
                            'order_id' => $order->id,
                            'user_id' => $order->user_id
                        ]);
                    } else {
                        Log::warning('Order not found for receipt generation', ['order_id' => $request->order_id]);
                    }
                } else {
                    Log::info('No order_id provided, creating temporary order for receipt');
                    // Create a temporary order for receipt generation if no order_id provided
                    $order = \App\Models\Order::create([
                        'order_id' => 'TEMP_' . time(),
                        'user_id' => auth()->id() ?? 1, // Default to admin if no auth
                        'customer_name' => $request->customer_name ?? 'Customer',
                        'customer_email' => $request->customer_email ?? 'customer@example.com',
                        'customer_phone' => $request->customer_phone ?? '0770000000',
                        'order_type' => 'dine_in', // Use valid order type
                        'items' => [['name' => 'Payment', 'quantity' => 1, 'price' => $request->amount, 'total' => $request->amount]],
                        'total_items' => 1,
                        'subtotal' => $request->amount,
                        'tax' => $request->amount * 0.1, // 10% tax
                        'total' => $request->amount * 1.1,
                        'payment_method' => $request->method,
                        'payment_status' => 'completed',
                        'status' => 'completed'
                    ]);
                    
                    Log::info('Temporary order created for receipt', ['order_id' => $order->id]);
                    
                    $receiptService = app(\App\Services\ReceiptService::class);
                    $receipt = $receiptService->generateReceipt(
                        $order, 
                        $result['transaction_id'], 
                        $request->method
                    );
                    
                    // Add receipt information to the response
                    $result['receipt'] = [
                        'receipt_number' => $receipt->receipt_number,
                        'receipt_url' => route('receipt.show', $receipt->receipt_number) . '?new_receipt=true'
                    ];
                    
                    Log::info('Receipt generated for payment without order', [
                        'receipt_number' => $receipt->receipt_number,
                        'order_id' => $order->id,
                        'amount' => $request->amount
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to generate receipt after payment', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'order_id' => $request->order_id,
                    'transaction_id' => $result['transaction_id'] ?? null
                ]);
                // Don't fail the payment if receipt generation fails
            }
        } else {
            Log::warning('Payment not successful or missing transaction_id', [
                'success' => $result['success'] ?? false,
                'transaction_id' => $result['transaction_id'] ?? null,
                'result' => $result
            ]);
        }

        return response()->json($result);
    }

    /**
     * Send mobile OTP
     */
    public function sendMobileOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'provider' => 'required|string|in:dialog,mobitel,hutch,airtel'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->paymentService->sendMobileOTP(
            $request->phone_number,
            $request->provider
        );

        return response()->json($result);
    }

    /**
     * Verify mobile OTP
     */
    public function verifyMobileOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_id' => 'required|string',
            'otp_code' => 'required|string|size:6',
            'test_otp' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP data',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->paymentService->verifyMobileOTP(
            $request->otp_id,
            $request->otp_code,
            $request->test_otp
        );

        return response()->json($result);
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(Request $request, $transactionId)
    {
        $result = $this->paymentService->verifyPayment($transactionId);
        return response()->json($result);
    }

    /**
     * Process refund
     */
    public function processRefund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'amount' => 'nullable|numeric|min:1',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid refund data',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->paymentService->processRefund(
            $request->transaction_id,
            $request->amount,
            $request->reason ?? 'Customer request'
        );

        return response()->json($result);
    }

    /**
     * Get supported payment methods
     */
    public function getSupportedMethods()
    {
        $result = $this->paymentService->getSupportedMethods();
        return response()->json($result);
    }

    /**
     * Get payment fees
     */
    public function getPaymentFees()
    {
        $result = $this->paymentService->getPaymentFees();
        return response()->json($result);
    }

    /**
     * Helper methods
     */
    private function maskCardNumber($cardNumber)
    {
        $cleaned = preg_replace('/\D/', '', $cardNumber);
        return substr($cleaned, 0, 4) . str_repeat('*', strlen($cleaned) - 8) . substr($cleaned, -4);
    }

    private function maskAccountNumber($accountNumber)
    {
        return str_repeat('*', strlen($accountNumber) - 4) . substr($accountNumber, -4);
    }

    /**
     * Get payment history (for admin)
     */
    public function getPaymentHistory(Request $request)
    {
        // This would typically fetch from database
        // For simulation, return sample data
        $payments = [
            [
                'id' => 1,
                'transaction_id' => 'CC_sim_20241220_abc123def456',
                'order_id' => 'ORD000001',
                'amount' => 1240.00,
                'method' => 'card',
                'status' => 'completed',
                'customer_name' => 'John Doe',
                'created_at' => now()->subHours(2)->toISOString()
            ],
            [
                'id' => 2,
                'transaction_id' => 'MP_sim_20241220_xyz789uvw012',
                'order_id' => 'ORD000002',
                'amount' => 850.00,
                'method' => 'mobile',
                'status' => 'completed',
                'customer_name' => 'Jane Smith',
                'created_at' => now()->subHours(5)->toISOString()
            ]
        ];

        return response()->json([
            'success' => true,
            'payments' => $payments,
            'total' => count($payments)
        ]);
    }
}