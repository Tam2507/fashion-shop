<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên phương thức (Visa, MasterCard, etc.)
            $table->string('code')->unique(); // Mã phương thức (visa, mastercard, etc.)
            $table->string('logo')->nullable(); // Đường dẫn logo
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt
            $table->integer('position')->default(0); // Thứ tự hiển thị
            $table->text('description')->nullable(); // Mô tả
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
