<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@fashionshop.vn'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Admin
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@fashionshop.vn'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'is_admin' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Regular admin (old)
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Example',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Customer
        \App\Models\User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Customer',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
