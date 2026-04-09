<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('orders_temp');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('review_photos');
    }

    public function down(): void
    {
        // Không rollback vì đây là bảng thừa
    }
};
