<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddWhiteColorVariant extends Command
{
    protected $signature = 'product:add-white-variant {product_id}';
    protected $description = 'Thêm variants màu Trắng cho sản phẩm';

    public function handle()
    {
        $productId = $this->argument('product_id');
        
        $product = Product::find($productId);
        
        if (!$product) {
            $this->error("Không tìm thấy sản phẩm với ID: {$productId}");
            return 1;
        }
        
        $this->info("Đang thêm variants màu Trắng cho sản phẩm: {$product->name}");
        
        $color = 'Trắng';
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $created = 0;
        
        foreach ($sizes as $size) {
            // Check if variant already exists
            $exists = $product->variants()
                ->where('color', $color)
                ->where('size', $size)
                ->exists();
            
            if ($exists) {
                $this->warn("Variant {$color} - {$size} đã tồn tại, bỏ qua.");
                continue;
            }
            
            // Generate SKU
            $colorSlug = 'TRA';
            $sku = "PRD{$product->id}-{$colorSlug}-{$size}";
            
            // Create variant
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $sku,
                'size' => $size,
                'color' => $color,
                'price' => $product->price,
                'stock_quantity' => 50, // Default stock
            ]);
            
            $this->info("✓ Đã tạo variant: {$color} - {$size}");
            $created++;
        }
        
        $this->info("Hoàn thành! Đã tạo {$created} variants màu Trắng.");
        
        return 0;
    }
}
