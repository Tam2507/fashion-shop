<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop existing table if it exists and recreate with correct structure
        Schema::dropIfExists('banners');
        
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_text')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('background_color')->default('#8B3A3A');
            $table->string('text_color')->default('#FFFFFF');
            $table->enum('banner_type', ['hero', 'promotion', 'announcement'])->default('hero');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
};