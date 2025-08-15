<?php

namespace App\Console\Commands;

use App\Services\SimulationPaymentService;
use App\Services\ReceiptService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestPaymentFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:test-flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test complete payment flow including receipt generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing complete payment flow...');
        
        // Test 1: Payment Service Response
        $this->info('1. Testing Payment Service Response...');
        $paymentService = new SimulationPaymentService();
        $paymentData = [
            'amount' => 500.00,
            'method' => 'card',
            'currency' => 'LKR',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '0771234567'
        ];
        
        $paymentResult = $paymentService->processPayment($paymentData);
        $this->info('Payment Result: ' . json_encode($paymentResult, JSON_PRETTY_PRINT));
        
        // Test 2: Receipt Generation
        if ($paymentResult['success'] && isset($paymentResult['transaction_id'])) {
            $this->info('2. Testing Receipt Generation...');
            
            try {
                // Create a test order
                $order = \App\Models\Order::create([
                    'order_id' => 'TEST_' . time(),
                    'user_id' => 1,
                    'customer_name' => 'Test Customer',
                    'customer_email' => 'test@example.com',
                    'customer_phone' => '0771234567',
                    'order_type' => 'dine_in', // Use valid order type
                    'items' => [['name' => 'Test Item', 'quantity' => 1, 'price' => 500.00, 'total' => 500.00]],
                    'total_items' => 1,
                    'subtotal' => 500.00,
                    'tax' => 50.00,
                    'total' => 550.00,
                    'payment_method' => 'card',
                    'payment_status' => 'completed',
                    'status' => 'completed'
                ]);
                
                $receiptService = new ReceiptService();
                $receipt = $receiptService->generateReceipt(
                    $order, 
                    $paymentResult['transaction_id'], 
                    'card'
                );
                
                $this->info('✅ Receipt generated successfully!');
                $this->info('- Receipt Number: ' . $receipt->receipt_number);
                $this->info('- Receipt ID: ' . $receipt->id);
                $this->info('- View URL: ' . route('receipt.show', $receipt->receipt_number));
                
                // Test 3: PDF Generation
                $this->info('3. Testing PDF Generation...');
                try {
                    $pdfData = $receiptService->generatePDFReceipt($receipt);
                    $this->info('✅ PDF generated successfully!');
                    $this->info('- Filename: ' . $pdfData['filename']);
                } catch (\Exception $e) {
                    $this->error('❌ PDF generation failed: ' . $e->getMessage());
                }
                
            } catch (\Exception $e) {
                $this->error('❌ Receipt generation failed: ' . $e->getMessage());
            }
        } else {
            $this->error('❌ Payment failed, cannot test receipt generation');
        }
        
        $this->info('Payment flow test completed!');
        return 0;
    }
}
