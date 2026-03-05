<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateProductVariants extends Command
{
    protected $signature = 'products:generate-variants';
    protected $description = 'Tự động tạo variants cho tất cả sản phẩm';

    public function handle()
    {
        $this->info('Bắt đầu tạo variants cho sản phẩm...');
        
        $products = Product::with(['images', 'variants', 'category'])->get();
        $created = 0;
        $skipped = 0;
        
        foreach ($products as $product) {
            // Check if product is accessory
            $isAccessory = $product->category && 
                           (stripos($product->category->name, 'phụ kiện') !== false || 
                            stripos($product->category->name, 'accessory') !== false ||
                            stripos($product->category->name, 'accessories') !== false);
            
            // Get available colors from images
            $colors = $product->images->pluck('color')->unique()->filter()->values();
            
            // If no colors from images, use default
            if ($colors->isEmpty()) {
                $colors = collect(['Mặc định']);
            }
            
            // Standard sizes
            $sizes = $isAccessory ? ['One Size'] : ['S', 'M', 'L', 'XL', 'XXL'];
            
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    // Check if variant already exists
                    $exists = $product->variants()
                        ->where('color', $color)
                        ->where('size', $size)
                        ->exists();
                    
                    if ($exists) {
                        $skipped++;
                        continue;
                    }
                    
                    // Generate SKU
                    $colorSlug = strtoupper(substr(Str::slug($color), 0, 3));
                    $sku = "PRD{$product->id}-{$colorSlug}-{$size}";
                    
                    // Create variant
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'size' => $size,
                        'color' => $color,
                        'price' => $product->price,
                        'stock_quantity' => (int)($product->quantity / (count($colors) * count($sizes))),
                    ]);
                    
                    $created++;
                }
            }
            
            $this->info("Sản phẩm: {$product->name} - Tạo variants với màu: " . $colors->implode(', '));
        }
        
        $this->info("Hoàn thành! Đã tạo {$created} variants mới, bỏ qua {$skipped} variants đã tồn tại.");
        
        return 0;
    }
}
