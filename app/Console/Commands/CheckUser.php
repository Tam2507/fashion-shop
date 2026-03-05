<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckUser extends Command
{
    protected $signature = 'user:check {email}';
    protected $description = 'Check if user exists and verify password';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            
            // List all users
            $this->info("\nAll users in database:");
            $users = User::all();
            foreach ($users as $u) {
                $this->line("- {$u->email} (Admin: " . ($u->is_admin ? 'Yes' : 'No') . ")");
            }
            
            return 1;
        }

        $this->info("User found!");
        $this->info("ID: {$user->id}");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Is Admin: " . ($user->is_admin ? 'Yes' : 'No'));
        $this->info("Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
        
        // Test password
        $testPassword = $this->ask('Enter password to test (or press Enter to skip)');
        if ($testPassword) {
            if (Hash::check($testPassword, $user->password)) {
                $this->info("✓ Password is correct!");
            } else {
                $this->error("✗ Password is incorrect!");
            }
        }

        return 0;
    }
}
