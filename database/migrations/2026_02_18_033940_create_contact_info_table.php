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
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('country')->default('Việt Nam');
            $table->string('hotline');
            $table->string('phone')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('email');
            $table->string('support_email')->nullable();
            $table->string('weekday_hours')->nullable();
            $table->string('weekend_hours')->nullable();
            $table->string('holiday_note')->nullable();
            $table->timestamps();
        });

        // Insert default data
        DB::table('contact_info')->insert([
            'address' => 'Lầu 1-2, 123 Nguyễn Văn Cừ',
            'city' => 'Quận 1, TP. Hồ Chí Minh',
            'country' => 'Việt Nam',
            'hotline' => '1900.1234',
            'phone' => '0901.234.567',
            'working_hours' => '8:00 - 22:00 (T2-CN)',
            'email' => 'info@fashionshop.vn',
            'support_email' => 'support@fashionshop.vn',
            'weekday_hours' => 'Thứ 2 - Thứ 6: 8:00 - 18:00',
            'weekend_hours' => 'Thứ 7 - Chủ nhật: 9:00 - 17:00',
            'holiday_note' => 'Lễ tết: Theo thông báo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_info');
    }
};
