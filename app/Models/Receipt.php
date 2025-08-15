<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'order_id',
        'user_id',
        'transaction_id',
        'payment_method',
        'payment_status',
        'subtotal',
        'tax',
        'discount',
        'total',
        'loyalty_points_earned',
        'receipt_data',
        'generated_at',
        'status'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'loyalty_points_earned' => 'integer',
        'receipt_data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rs. ' . number_format($this->subtotal, 2);
    }

    public function getFormattedTaxAttribute()
    {
        return 'Rs. ' . number_format($this->tax, 2);
    }

    public function getFormattedDiscountAttribute()
    {
        return 'Rs. ' . number_format($this->discount, 2);
    }

    public function getFormattedGeneratedAtAttribute()
    {
        return $this->generated_at->format('M d, Y g:i A');
    }

    public function getReceiptNumberDisplayAttribute()
    {
        return 'RCPT-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
