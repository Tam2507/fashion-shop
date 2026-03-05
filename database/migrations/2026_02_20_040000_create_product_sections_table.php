<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_sections')) {
            Schema::create('product_sections', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Tên section: Sản phẩm nổi bật, Bán chạy, etc.
                $table->string('slug')->unique(); // featured-products, best-sellers, etc.
                $table->text('description')->nullable();
                $table->integer('display_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->integer('max_products')->default(8); // Số sản phẩm tối đa hiển thị
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_section_items')) {
            Schema::create('product_section_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_id')->constrained('product_sections')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->integer('display_order')->default(0);
                $table->timestamps();
                
                $table->unique(['section_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_section_items');
        Schema::dropIfExists('product_sections');
    }
};
