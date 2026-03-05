<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Áo', 'slug' => 'ao', 'description' => 'Áo thời trang']);
        Category::create(['name' => 'Quần', 'slug' => 'quan', 'description' => 'Quần thời trang']);
        Category::create(['name' => 'Giày', 'slug' => 'giay', 'description' => 'Giày thời trang']);
        Category::create(['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'description' => 'Phụ kiện thời trang']);
    }
}
