<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Update superadmin user to be admin
        DB::table('users')
            ->where('email', 'superadmin@fashionshop.vn')
            ->update([
                'is_admin' => 1,
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        DB::table('users')
            ->where('email', 'superadmin@fashionshop.vn')
            ->update([
                'is_admin' => 0,
                'updated_at' => now(),
            ]);
    }
};
