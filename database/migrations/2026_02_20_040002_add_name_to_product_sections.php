<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_sections', 'name')) {
            Schema::table('product_sections', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }
        
        if (!Schema::hasColumn('product_sections', 'slug')) {
            Schema::table('product_sections', function (Blueprint $table) {
                $table->string('slug')->unique()->after('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_sections', function (Blueprint $table) {
            if (Schema::hasColumn('product_sections', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('product_sections', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
