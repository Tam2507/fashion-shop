<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Column is string type - no ALTER TABLE needed
        // 'shipping' status is already valid as a string value
    }

    public function down(): void
    {
        // Nothing to revert
    }
};
