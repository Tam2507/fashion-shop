<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size', 20)->nullable()->after('sku');
            $table->string('color', 50)->nullable()->after('size');
            $table->decimal('price_adjustment', 10, 2)->nullable()->after('color');
            $table->renameColumn('quantity', 'stock_quantity');
            
            // Add unique constraint for product_id + size + color
            $table->unique(['product_id', 'size', 'color'], 'unique_variant');
        });
    }

    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropUnique('unique_variant');
            $table->dropColumn(['size', 'color', 'price_adjustment']);
            $table->renameColumn('stock_quantity', 'quantity');
        });
    }
};
