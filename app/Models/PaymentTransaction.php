<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'gateway',
        'transaction_id',
        'gateway_transaction_id',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'webhook_data',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'webhook_data' => 'array',
    ];
    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'failed' => 'Thất bại',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
            default => 'Không xác định'
        };
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
