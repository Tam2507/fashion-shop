<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Add composite index for faster lookups
            $table->index(['user_id', 'product_id', 'variant_id'], 'carts_user_product_variant_index');
            $table->index('user_id', 'carts_user_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('carts_user_product_variant_index');
            $table->dropIndex('carts_user_id_index');
        });
    }
};
