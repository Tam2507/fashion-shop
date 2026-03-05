<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->string('color', 50)->nullable()->after('path');
            $table->index('color');
        });
    }

    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['color']);
            $table->dropColumn('color');
        });
    }
};
