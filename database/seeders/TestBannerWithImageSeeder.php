<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Illuminate\Support\Facades\File;

class TestBannerWithImageSeeder extends Seeder
{
    public function run()
    {
        // Create banners directory if not exists
        $bannersPath = storage_path('app/public/banners');
        if (!File::exists($bannersPath)) {
            File::makeDirectory($bannersPath, 0755, true);
        }

        // Create a simple test image (1x1 pixel PNG)
        $testImageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $testImagePath = $bannersPath . '/test-banner.png';
        File::put($testImagePath, $testImageData);

        // Update first banner with test image
        $banner = Banner::find(1);
        if ($banner) {
            $banner->update([
                'image' => 'banners/test-banner.png'
            ]);
            echo "✓ Banner ID 1 updated with test image\n";
            echo "Image path: banners/test-banner.png\n";
        } else {
            echo "✗ Banner ID 1 not found\n";
        }
    }
}
