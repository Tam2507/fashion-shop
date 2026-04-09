<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearReviews extends Command
{
    protected $signature = 'reviews:clear';
    protected $description = 'Xóa toàn bộ đánh giá giả trong database';

    public function handle()
    {
        $count = DB::table('reviews')->count();
        DB::table('reviews')->truncate();
        $this->info("Đã xóa {$count} đánh giá.");
    }
}
