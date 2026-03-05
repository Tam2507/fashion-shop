<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixAdminUser extends Command
{
    protected $signature = 'user:fix-admin {email}';
    protected $description = 'Fix user to be admin';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("User {$user->name} ({$email}) is now an admin!");
        $this->info("is_admin value: " . $user->is_admin);
        
        return 0;
    }
}
