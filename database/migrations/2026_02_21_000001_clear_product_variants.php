<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clear all existing variants (compatible with MySQL and PostgreSQL)
        DB::statement('DELETE FROM product_variants');
    }

    public function down(): void
    {
        // Cannot restore deleted data
    }
};
