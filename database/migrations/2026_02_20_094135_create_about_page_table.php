<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_page', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Về Fashion Shop');
            $table->text('intro')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('core_values')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_page');
    }
};
