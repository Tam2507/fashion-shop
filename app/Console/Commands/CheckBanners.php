<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Banner;

class CheckBanners extends Command
{
    protected $signature = 'banner:check';
    protected $description = 'Check all banners in database';

    public function handle()
    {
        $this->info("Checking banners...\n");
        
        $banners = Banner::orderBy('position')->get();
        
        if ($banners->isEmpty()) {
            $this->error("No banners found in database!");
            return 1;
        }
        
        $this->info("Total banners: " . $banners->count() . "\n");
        
        foreach ($banners as $banner) {
            $this->line("-----------------------------------");
            $this->info("ID: {$banner->id}");
            $this->info("Title: {$banner->title}");
            $this->info("Page: {$banner->page}");
            $this->info("Image: {$banner->image}");
            $this->info("Is Active: " . ($banner->is_active ? 'Yes' : 'No'));
            $this->info("Position: {$banner->position}");
            
            // Check if image file exists
            if ($banner->image) {
                $imagePath = storage_path('app/public/' . $banner->image);
                if (file_exists($imagePath)) {
                    $this->info("Image file: EXISTS ✓");
                } else {
                    $this->error("Image file: NOT FOUND ✗");
                    $this->error("Looking for: {$imagePath}");
                }
            }
        }
        
        $this->line("\n-----------------------------------");
        $this->info("\nActive home banners:");
        $homeBanners = Banner::active()->forPage('home')->ordered()->get();
        $this->info("Count: " . $homeBanners->count());
        
        foreach ($homeBanners as $banner) {
            $this->line("- {$banner->title} (Position: {$banner->position})");
        }
        
        return 0;
    }
}
