<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'price', 'quantity', 'image', 'image_color', 'slug', 'is_active', 'seo_title', 'seo_description', 'meta_keywords'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('approved', true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Get unique colors from both variants and images
    public function getAvailableColors()
    {
        // Get colors from variants
        $variantColors = $this->variants()->distinct()->pluck('color')->filter();
        
        // Get colors from images
        $imageColors = $this->images()->whereNotNull('color')->distinct()->pluck('color')->filter();
        
        // Get color from main image
        $mainImageColor = collect($this->image_color ? [$this->image_color] : []);
        
        // Merge and get unique colors
        return $variantColors->merge($imageColors)->merge($mainImageColor)->unique()->sort()->values();
    }

    // Get unique sizes from variants
    public function getAvailableSizes()
    {
        $sizes = $this->variants()->distinct()->pluck('size')->filter();
        
        // Define size order
        $sizeOrder = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6, '2XL' => 6, '3XL' => 7];
        
        return $sizes->sort(function($a, $b) use ($sizeOrder) {
            $orderA = $sizeOrder[strtoupper($a)] ?? 999;
            $orderB = $sizeOrder[strtoupper($b)] ?? 999;
            return $orderA <=> $orderB;
        })->values();
    }

    // Get images for specific color
    public function getImagesForColor($color)
    {
        $colorImages = $this->images()->forColor($color)->get();
        
        // If no color-specific images, return all images
        return $colorImages->isEmpty() ? $this->images : $colorImages;
    }
}
