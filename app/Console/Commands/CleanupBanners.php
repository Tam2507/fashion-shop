<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Banner;

class CleanupBanners extends Command
{
    protected $signature = 'banner:cleanup';
    protected $description = 'Delete banners without images';

    public function handle()
    {
        $this->info("Cleaning up banners without images...\n");
        
        $bannersWithoutImage = Banner::whereNull('image')->orWhere('image', '')->get();
        
        if ($bannersWithoutImage->isEmpty()) {
            $this->info("No banners to clean up!");
            return 0;
        }
        
        $this->info("Found {$bannersWithoutImage->count()} banners without images:\n");
        
        foreach ($bannersWithoutImage as $banner) {
            $this->line("- ID: {$banner->id}, Title: {$banner->title}, Page: {$banner->page}");
        }
        
        if ($this->confirm('Do you want to delete these banners?', true)) {
            foreach ($bannersWithoutImage as $banner) {
                $banner->delete();
                $this->info("✓ Deleted banner ID: {$banner->id}");
            }
            
            $this->info("\n✓ Cleanup completed!");
        } else {
            $this->info("Cleanup cancelled.");
        }
        
        return 0;
    }
}
