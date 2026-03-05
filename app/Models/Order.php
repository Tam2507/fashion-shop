<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'guest_email',
        'total_price', 
        'status', 
        'shipping_address',
        'billing_address',
        'coupon_code',
        'discount_amount',
        'shipping_cost',
        'phone',
        'notes',
        'payment_method_id'
    ];
    
    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'billing_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Alias for backward compatibility
    public function items(): HasMany
    {
        return $this->orderItems();
    }
    
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
    
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
    
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }
    
    // Computed attributes
    public function getTotalItemsAttribute(): int
    {
        return $this->orderItems()->sum('quantity');
    }
    
    public function getSubtotalAttribute(): float
    {
        return $this->orderItems()->sum(DB::raw('price * quantity'));
    }
    
    public function getFinalTotalAttribute(): float
    {
        return $this->total_price + $this->shipping_cost - $this->discount_amount;
    }
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'received' => 'Đã nhận',
            'processing' => 'Đang xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipped' => 'Đã gửi hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
            default => 'Không xác định'
        };
    }
    
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'received' => 'warning',
            'processing' => 'info',
            'confirmed' => 'primary',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary'
        };
    }
    
    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    public function scopePending($query)
    {
        return $query->whereIn('status', ['received', 'processing']);
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
