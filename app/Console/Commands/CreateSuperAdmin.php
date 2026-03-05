<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:create-super';
    protected $description = 'Create or update super admin account';

    public function handle()
    {
        $email = 'superadmin@fashionshop.vn';
        $password = 'admin123';
        
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $this->info("User already exists. Updating...");
            $user->update([
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ]);
            $this->info("✓ Super Admin account updated!");
        } else {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ]);
            $this->info("✓ Super Admin account created!");
        }
        
        $this->line('');
        $this->line('Login credentials:');
        $this->line('Email: ' . $email);
        $this->line('Password: ' . $password);
        $this->line('');
        $this->line('User details:');
        $this->line('ID: ' . $user->id);
        $this->line('Name: ' . $user->name);
        $this->line('Email: ' . $user->email);
        $this->line('Is Admin: ' . ($user->is_admin ? 'YES' : 'NO'));
        $this->line('Email Verified: ' . ($user->email_verified_at ? 'YES' : 'NO'));
        
        return 0;
    }
}
