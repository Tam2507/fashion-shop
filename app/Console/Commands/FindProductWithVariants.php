<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class FindProductWithVariants extends Command
{
    protected $signature = 'product:find-with-variants';
    protected $description = 'Tìm sản phẩm có 20 variants';

    public function handle()
    {
        $products = Product::withCount('variants')->get();
        
        foreach ($products as $product) {
            if ($product->variants_count == 20) {
                $this->info("Product ID: {$product->id}");
                $this->info("Name: {$product->name}");
                $this->info("Variants: {$product->variants_count}");
                
                // Show colors
                $colors = $product->variants()->distinct()->pluck('color');
                $this->info("Colors: " . $colors->implode(', '));
                
                return 0;
            }
        }
        
        $this->error("Không tìm thấy sản phẩm có 20 variants");
        return 1;
    }
}
