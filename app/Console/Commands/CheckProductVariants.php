<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CheckProductVariants extends Command
{
    protected $signature = 'product:check-variants {product_id}';
    protected $description = 'Kiểm tra variants của sản phẩm';

    public function handle()
    {
        $productId = $this->argument('product_id');
        $product = Product::with('variants')->find($productId);
        
        if (!$product) {
            $this->error("Không tìm thấy sản phẩm");
            return 1;
        }
        
        $this->info("Product: {$product->name}");
        $this->info("Total variants: " . $product->variants->count());
        
        $colors = $product->variants()->distinct()->pluck('color');
        $this->info("Colors: " . $colors->implode(', '));
        
        // Show all variants
        $this->table(
            ['ID', 'SKU', 'Color', 'Size', 'Stock'],
            $product->variants->map(function($v) {
                return [$v->id, $v->sku, $v->color, $v->size, $v->stock_quantity];
            })
        );
        
        return 0;
    }
}
