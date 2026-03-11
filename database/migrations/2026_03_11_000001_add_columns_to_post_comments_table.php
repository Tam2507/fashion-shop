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
        Schema::table('post_comments', function (Blueprint $table) {
            $table->foreignId('post_id')->after('id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->after('post_id')->constrained('users')->onDelete('cascade');
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->text('content')->after('guest_email');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['post_id', 'user_id', 'guest_name', 'guest_email', 'content', 'status']);
        });
    }
};
