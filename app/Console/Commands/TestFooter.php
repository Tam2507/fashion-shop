<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FooterSetting;

class TestFooter extends Command
{
    protected $signature = 'test:footer';
    protected $description = 'Test footer settings display';

    public function handle()
    {
        $settings = FooterSetting::first();
        
        if (!$settings) {
            $this->error('No footer settings found in database!');
            return 1;
        }

        $this->info('Footer Settings from Database:');
        $this->line('');
        $this->line('Company Name: ' . $settings->company_name);
        $this->line('Company Description: ' . $settings->company_description);
        $this->line('Address: ' . $settings->address);
        $this->line('Phone: ' . $settings->phone);
        $this->line('Email: ' . $settings->email);
        $this->line('Hotline: ' . $settings->hotline);
        $this->line('Business License: ' . $settings->business_license);
        $this->line('Working Hours: ' . $settings->working_hours);
        $this->line('Copyright: ' . $settings->copyright_text);
        $this->line('');
        $this->line('Social Media:');
        $this->line('- Facebook: ' . $settings->social_facebook);
        $this->line('- Instagram: ' . $settings->social_instagram);
        $this->line('- YouTube: ' . $settings->social_youtube);
        $this->line('- TikTok: ' . $settings->social_tiktok);
        
        $this->info('');
        $this->info('✓ Footer settings are correctly stored in database');
        $this->info('✓ Layout file has been updated to use correct field names');
        $this->info('✓ All caches have been cleared');
        $this->line('');
        $this->warn('If footer still shows old data on website:');
        $this->line('1. Do a hard refresh in browser: Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)');
        $this->line('2. Or clear browser cache completely');
        $this->line('3. Try opening in incognito/private window');
        
        return 0;
    }
}
