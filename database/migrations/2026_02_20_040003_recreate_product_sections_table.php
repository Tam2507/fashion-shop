<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old tables if they exist
        Schema::dropIfExists('product_section_items');
        Schema::dropIfExists('product_sections');
        
        // Create new product_sections table with all columns
        Schema::create('product_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('max_products')->default(8);
            $table->timestamps();
        });

        // Create product_section_items table
        Schema::create('product_section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('product_sections')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->unique(['section_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_section_items');
        Schema::dropIfExists('product_sections');
    }
};
