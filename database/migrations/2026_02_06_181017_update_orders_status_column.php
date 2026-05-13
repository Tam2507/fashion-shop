<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing status values to new ones
        DB::table('orders')->where('status', 'pending')->update(['status' => 'received']);
        // Column is string type - no ALTER TABLE needed
    }

    public function down(): void
    {
        DB::table('orders')->where('status', 'received')->update(['status' => 'pending']);
    }
};
