<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;

class UpdateVariantsWithColorsSeeder extends Seeder
{
    public function run()
    {
        // Get all products
        $products = Product::with('variants', 'images')->get();
        
        $colors = ['Đen', 'Trắng', 'Xanh', 'Đỏ', 'Vàng', 'Xám'];
        $sizes = ['S', 'M', 'L', 'XL']; // Ordered sizes
        
        foreach ($products as $product) {
            // If product has no variants, create some
            if ($product->variants->count() == 0) {
                // Create 2-3 color variants with different sizes
                $numColors = rand(2, 3);
                $selectedColors = array_slice($colors, 0, $numColors);
                
                foreach ($selectedColors as $color) {
                    // Use all sizes in order
                    foreach ($sizes as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => "VAR-{$product->id}-{$color}-{$size}",
                            'size' => $size,
                            'color' => $color,
                            'option_name' => "{$size} - {$color}",
                            'price' => $product->price + rand(-10000, 20000),
                            'price_adjustment' => rand(-5000, 10000),
                            'stock_quantity' => rand(5, 50),
                        ]);
                    }
                }
            } else {
                // Update existing variants with size and color
                foreach ($product->variants as $index => $variant) {
                    if (!$variant->size || !$variant->color) {
                        $color = $colors[$index % count($colors)];
                        $size = $sizes[$index % count($sizes)];
                        
                        $variant->update([
                            'size' => $size,
                            'color' => $color,
                            'option_name' => "{$size} - {$color}",
                            'stock_quantity' => $variant->quantity ?? rand(5, 50),
                        ]);
                    }
                }
            }
            
            // Assign colors to images
            if ($product->images->count() > 0) {
                $availableColors = $product->variants()->distinct()->pluck('color')->toArray();
                
                if (count($availableColors) > 0) {
                    foreach ($product->images as $index => $image) {
                        // Assign color to image (distribute evenly)
                        $color = $availableColors[$index % count($availableColors)];
                        $image->update(['color' => $color]);
                    }
                }
            }
        }
        
        $this->command->info('Variants and images updated with colors successfully!');
    }
}
