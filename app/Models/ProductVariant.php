<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'sku', 
        'size', 
        'color', 
        'option_name', 
        'price', 
        'price_adjustment',
        'stock_quantity', 
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
        'price_adjustment' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate final price
    public function getFinalPriceAttribute()
    {
        // Always use product base price, ignore price_adjustment
        return $this->product->price;
    }

    // Check if in stock
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    // Scope: Find by size and color
    public function scopeForVariant($query, $size, $color)
    {
        return $query->where('size', $size)->where('color', $color);
    }
}
