<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email} {password} {name}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Check if user exists
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->error("User with email {$email} already exists!");
            $this->info("ID: {$existingUser->id}");
            $this->info("Name: {$existingUser->name}");
            $this->info("Email: {$existingUser->email}");
            $this->info("Is Admin: " . ($existingUser->is_admin ? 'Yes' : 'No'));
            return 1;
        }

        // Create new admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("Admin user created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("Name: {$name}");

        return 0;
    }
}
