<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_sections', 'description')) {
            Schema::table('product_sections', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }
        
        if (!Schema::hasColumn('product_sections', 'display_order')) {
            Schema::table('product_sections', function (Blueprint $table) {
                $table->integer('display_order')->default(0);
            });
        }
        
        if (!Schema::hasColumn('product_sections', 'is_active')) {
            Schema::table('product_sections', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }
        
        if (!Schema::hasColumn('product_sections', 'max_products')) {
            Schema::table('product_sections', function (Blueprint $table) {
                $table->integer('max_products')->default(8);
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_sections', function (Blueprint $table) {
            if (Schema::hasColumn('product_sections', 'max_products')) {
                $table->dropColumn('max_products');
            }
            if (Schema::hasColumn('product_sections', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('product_sections', 'display_order')) {
                $table->dropColumn('display_order');
            }
            if (Schema::hasColumn('product_sections', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
