<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Services\ReceiptService;
use Illuminate\Console\Command;

class TestReceiptGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipt:test {--user-id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test receipt generation for an existing order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        
        // Find a user
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        // Find an order for this user
        $order = Order::where('user_id', $userId)->first();
        if (!$order) {
            $this->error("No orders found for user {$user->name}");
            return 1;
        }
        
        $this->info("Testing receipt generation for:");
        $this->info("- User: {$user->name} (ID: {$user->id})");
        $this->info("- Order: {$order->order_id} (Total: Rs. {$order->total})");
        
        try {
            $receiptService = app(ReceiptService::class);
            $receipt = $receiptService->generateReceipt(
                $order, 
                'TEST_TXN_' . time(), 
                'card'
            );
            
            $this->info("✅ Receipt generated successfully!");
            $this->info("- Receipt Number: {$receipt->receipt_number}");
            $this->info("- Receipt ID: {$receipt->id}");
            $this->info("- Loyalty Points: {$receipt->loyalty_points_earned}");
            $this->info("- View URL: " . route('receipt.show', $receipt->receipt_number));
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to generate receipt: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
