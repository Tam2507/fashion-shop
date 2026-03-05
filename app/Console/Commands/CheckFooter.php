<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FooterSetting;

class CheckFooter extends Command
{
    protected $signature = 'footer:check';
    protected $description = 'Check footer settings';

    public function handle()
    {
        $footer = FooterSetting::first();
        
        if (!$footer) {
            $this->error("No footer settings found in database!");
            return 1;
        }
        
        $this->info("Footer Settings:");
        $this->line("Company Name: " . ($footer->company_name ?? 'NULL'));
        $this->line("Phone: " . ($footer->phone ?? 'NULL'));
        $this->line("Email: " . ($footer->email ?? 'NULL'));
        $this->line("Facebook: " . ($footer->facebook_url ?? 'NULL'));
        $this->line("Instagram: " . ($footer->instagram_url ?? 'NULL'));
        $this->line("\nCompany Info:");
        $this->line($footer->company_info ?? 'NULL');
        
        return 0;
    }
}
