<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('fas fa-star');
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('background_color')->default('#8B3A3A');
            $table->string('icon_color')->default('#FFFFFF');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};