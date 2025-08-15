<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestCompleteFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flow:test-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test complete order submission flow including receipt generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing complete order submission flow...');
        
        // Authenticate as admin user
        $user = User::find(1);
        if (!$user) {
            $this->error('Admin user not found');
            return 1;
        }
        
        Auth::login($user);
        
        // Test order data (similar to what frontend would send)
        $orderData = [
            'order_type' => 'dine_in',
            'items' => [
                [
                    'id' => 1, // Need to provide actual menu item ID
                    'quantity' => 2
                ]
            ],
            'special_instructions' => 'Extra hot please',
            'payment_method' => 'card',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '0771234567',
            'transaction_id' => 'TEST_TXN_' . time()
        ];
        
        $this->info('1. Testing order submission...');
        
        try {
            // Create a mock request
            $request = new Request($orderData);
            $request->merge([
                'amount' => 550.00, // Including tax
                'transaction_id' => 'TEST_TXN_' . time()
            ]);
            
            // Call the OrderController store method
            $orderController = app(\App\Http\Controllers\OrderController::class);
            $response = $orderController->store($request);
            
            $this->info('✅ Order submitted successfully!');
            
            // Check if receipt was generated
            if (isset($response->getData()->receipt)) {
                $this->info('✅ Receipt generated!');
                $this->info('- Receipt Number: ' . $response->getData()->receipt->receipt_number);
                $this->info('- Receipt URL: ' . $response->getData()->receipt->receipt_url);
                
                // Test receipt access
                $this->info('2. Testing receipt access...');
                $receiptController = app(\App\Http\Controllers\ReceiptController::class);
                $receipt = $receiptController->show($response->getData()->receipt->receipt_number);
                
                if ($receipt) {
                    $this->info('✅ Receipt view works!');
                } else {
                    $this->error('❌ Receipt view failed');
                }
                
            } else {
                $this->warn('⚠️ No receipt generated');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Order submission failed: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        $this->info('Complete flow test finished!');
        return 0;
    }
}
