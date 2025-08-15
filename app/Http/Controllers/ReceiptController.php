<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
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
=======
use App\Models\Receipt;
use App\Models\Order;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    /**
     * Show receipt after successful payment
     */
    public function show($receiptNumber)
    {
        $receipt = $this->receiptService->getReceiptByNumber($receiptNumber);
        
        if (!$receipt) {
            abort(404, 'Receipt not found');
        }

        // Check if user has permission to view this receipt
        if (Auth::id() !== $receipt->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Add success message for newly generated receipts
        if (request()->has('new_receipt')) {
            session()->flash('success', 'Receipt generated successfully! You can now view, print, or download your receipt.');
        }

        return view('receipt.show', compact('receipt'));
    }

    /**
     * Show receipt by order ID
     */
    public function showByOrder($orderId)
    {
        $receipt = Receipt::where('order_id', $orderId)
            ->with(['order', 'user'])
            ->first();

        if (!$receipt) {
            abort(404, 'Receipt not found for this order');
        }

        // Check if user has permission to view this receipt
        if (Auth::id() !== $receipt->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        return view('receipt.show', compact('receipt'));
    }

    /**
     * Download receipt as PDF
     */
    public function downloadPDF($receiptNumber)
    {
        $receipt = $this->receiptService->getReceiptByNumber($receiptNumber);
        
        if (!$receipt) {
            abort(404, 'Receipt not found');
        }

        // Check if user has permission to download this receipt
        if (Auth::id() !== $receipt->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        try {
            // Generate PDF
            $pdfData = $this->receiptService->generatePDFReceipt($receipt);
            
            // Download the PDF
            return $pdfData['pdf']->download($pdfData['filename']);
            
        } catch (\Exception $e) {
            Log::error('Failed to download PDF receipt', [
                'receipt_number' => $receiptNumber,
                'error' => $e->getMessage()
            ]);
            
            return back()->withErrors(['message' => 'Failed to generate PDF. Please try again.']);
        }
    }

    /**
     * Print receipt view
     */
    public function print($receiptNumber)
    {
        $receipt = $this->receiptService->getReceiptByNumber($receiptNumber);
        
        if (!$receipt) {
            abort(404, 'Receipt not found');
        }

        // Check if user has permission to print this receipt
        if (Auth::id() !== $receipt->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        return view('receipt.print', compact('receipt'));
    }

    /**
     * Get user's receipt history
     */
    public function history()
    {
        $receipts = $this->receiptService->getUserReceipts(Auth::id(), 15);
        
        return view('receipt.history', compact('receipts'));
    }

    /**
     * Search receipt by number
     */
    public function search(Request $request)
    {
        $request->validate([
            'receipt_number' => 'required|string|max:50'
        ]);

        $receipt = $this->receiptService->getReceiptByNumber($request->receipt_number);
        
        if (!$receipt) {
            return back()->withErrors(['receipt_number' => 'Receipt not found']);
        }

        // Check if user has permission to view this receipt
        if (Auth::id() !== $receipt->user_id && !Auth::user()->isAdmin()) {
            return back()->withErrors(['receipt_number' => 'Unauthorized access']);
        }

        return redirect()->route('receipt.show', $receipt->receipt_number);
    }

    /**
     * Admin: View all receipts
     */
    public function adminIndex(Request $request)
    {
        $this->middleware('admin');

        $query = Receipt::with(['order', 'user']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('generated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('generated_at', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $receipts = $query->orderBy('generated_at', 'desc')->paginate(20);

        return view('admin.receipts.index', compact('receipts'));
    }

    /**
     * Admin: View specific receipt
     */
    public function adminShow($receiptNumber)
    {
        $this->middleware('admin');

        $receipt = $this->receiptService->getReceiptByNumber($receiptNumber);
        
        if (!$receipt) {
            abort(404, 'Receipt not found');
        }

        return view('admin.receipts.show', compact('receipt'));
    }

    /**
     * Admin: Cancel receipt
     */
    public function adminCancel($receiptId)
    {
        $this->middleware('admin');

        try {
            $receipt = $this->receiptService->cancelReceipt($receiptId);
            
            return response()->json([
                'success' => true,
                'message' => 'Receipt cancelled successfully',
                'receipt' => $receipt
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to cancel receipt', [
                'receipt_id' => $receiptId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel receipt'
            ], 500);
        }
    }
}
>>>>>>> 75ff405 (Update message~)
