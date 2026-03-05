<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'applicable_products',
        'applicable_categories',
    ];
    
    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];
    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }
    
    public function isValid(): bool
    {
        return $this->is_active &&
               $this->starts_at <= now() &&
               $this->expires_at >= now() &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
    
    public function canBeUsedBy($userId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }
        
        // Check per-customer usage limit if needed
        $maxUsagePerCustomer = config('ecommerce.coupons.max_usage_per_customer', 1);
        if ($userId && $maxUsagePerCustomer > 0) {
            $customerUsage = $this->orders()->where('user_id', $userId)->count();
            return $customerUsage < $maxUsagePerCustomer;
        }
        
        return true;
    }
    
    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->isValid()) {
            return 0;
        }
        
        if ($this->minimum_amount && $orderAmount < $this->minimum_amount) {
            return 0;
        }
        
        $discount = match($this->type) {
            'percentage' => $orderAmount * ($this->value / 100),
            'fixed_amount' => $this->value,
            'free_shipping' => 0, // Handled separately
            default => 0
        };
        
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }
        
        return $discount;
    }
    
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'percentage' => 'Giảm theo %',
            'fixed_amount' => 'Giảm cố định',
            'free_shipping' => 'Miễn phí vận chuyển',
            default => 'Không xác định'
        };
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('expires_at', '>=', now());
    }
    
    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('used_count < usage_limit');
                    });
    }
}
