<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class CleanupBannersSeeder extends Seeder
{
    public function run()
    {
        echo "Deleting banners without images...\n";
        
        $deleted = Banner::whereNull('image')->orWhere('image', '')->delete();
        
        echo "✓ Deleted {$deleted} banners\n";
        
        echo "\nRemaining banners:\n";
        $banners = Banner::all();
        foreach ($banners as $banner) {
            echo "- ID: {$banner->id}, Title: {$banner->title}, Page: {$banner->page}, Image: {$banner->image}\n";
        }
    }
}
