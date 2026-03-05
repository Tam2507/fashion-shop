<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixSuperAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'superadmin@fashionshop.vn')->first();
        
        if ($user) {
            $user->update([
                'is_admin' => true,
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
            
            echo "✓ Super Admin updated successfully!\n";
            echo "Email: superadmin@fashionshop.vn\n";
            echo "Password: admin123\n";
            echo "Is Admin: Yes\n";
        } else {
            echo "✗ User not found!\n";
        }
    }
}
