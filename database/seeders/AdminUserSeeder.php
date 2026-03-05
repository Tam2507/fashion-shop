<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Tạo admin user nếu chưa có
        if (!User::where('email', 'admin@fashionshop.vn')->exists()) {
            User::create([
                'name' => 'Admin Fashion Shop',
                'email' => 'admin@fashionshop.vn',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);
            
            echo "Admin user created: admin@fashionshop.vn / admin123\n";
        } else {
            echo "Admin user already exists\n";
        }
    }
}