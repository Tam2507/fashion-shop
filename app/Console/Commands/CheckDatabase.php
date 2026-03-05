<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Banner;
use App\Models\FooterSetting;

class CheckDatabase extends Command
{
    protected $signature = 'db:check';
    protected $description = 'Check database content';

    public function handle()
    {
        $this->info('=== DATABASE CONTENT ===');
        $this->line('');
        
        // Users
        $totalUsers = User::count();
        $admins = User::where('is_admin', 1)->count();
        $customers = User::where('is_admin', 0)->count();
        $this->line("👥 Users: {$totalUsers} total ({$admins} admins, {$customers} customers)");
        
        // Products
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', 1)->count();
        $this->line("📦 Products: {$totalProducts} total ({$activeProducts} active)");
        
        // Categories
        $totalCategories = Category::count();
        $this->line("🏷️  Categories: {$totalCategories}");
        
        // Orders
        $totalOrders = Order::count();
        $this->line("🛒 Orders: {$totalOrders}");
        
        if ($totalOrders > 0) {
            $this->line('');
            $this->line('Order Status Breakdown:');
            $statuses = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
            foreach ($statuses as $status) {
                $this->line("  - {$status->status}: {$status->count}");
            }
        }
        
        // Banners
        $totalBanners = Banner::count();
        $activeBanners = Banner::where('is_active', 1)->count();
        $this->line("🖼️  Banners: {$totalBanners} total ({$activeBanners} active)");
        
        // Footer Settings
        $footerSettings = FooterSetting::first();
        $this->line("⚙️  Footer Settings: " . ($footerSettings ? 'Configured' : 'Not configured'));
        
        $this->line('');
        $this->line('Admin Accounts:');
        $adminUsers = User::where('is_admin', 1)->get();
        foreach ($adminUsers as $admin) {
            $this->line("  - {$admin->name} ({$admin->email})");
        }
        
        $this->line('');
        $this->info('✓ Database check complete!');
        
        return 0;
    }
}
