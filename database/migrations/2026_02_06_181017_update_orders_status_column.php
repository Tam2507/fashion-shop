<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing status values to new ones
        DB::table('orders')->where('status', 'pending')->update(['status' => 'received']);
        
        // For MySQL, we need to modify the enum column
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('received', 'processing', 'confirmed', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'received'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values
        DB::table('orders')->where('status', 'received')->update(['status' => 'pending']);
        
        // Revert enum to old values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};
