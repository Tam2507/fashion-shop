<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'provider',
        'tracking_number',
        'provider_shipment_id',
        'status',
        'shipping_cost',
        'estimated_delivery',
        'actual_delivery',
        'provider_response',
    ];
    
    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'estimated_delivery' => 'date',
        'actual_delivery' => 'datetime',
        'provider_response' => 'array',
    ];
    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'created' => 'Đã tạo',
            'picked_up' => 'Đã lấy hàng',
            'in_transit' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'failed' => 'Giao hàng thất bại',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }
    
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'created' => 'secondary',
            'picked_up' => 'info',
            'in_transit' => 'primary',
            'delivered' => 'success',
            'failed' => 'danger',
            'cancelled' => 'warning',
            default => 'secondary'
        };
    }
}
