<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo products (use firstOrCreate to avoid duplicates)
        \App\Models\Product::firstOrCreate(
            ['slug' => 'ao-thun-basic'],
            [
                'category_id' => 1,
                'name' => 'Áo thun Basic',
                'description' => 'Áo thun cotton cơ bản',
                'price' => 199000,
                'quantity' => 100,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'quan-jean'],
            [
                'category_id' => 2,
                'name' => 'Quần jean',
                'description' => 'Quần jean nam',
                'price' => 399000,
                'quantity' => 50,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'giay-sneaker'],
            [
                'category_id' => 3,
                'name' => 'Giày sneaker',
                'description' => 'Giày sneaker thời trang',
                'price' => 899000,
                'quantity' => 30,
            ]
        );

        // Additional fashion items
        \App\Models\Product::firstOrCreate(
            ['slug' => 'ao-khoac-denim'],
            [
                'category_id' => 1,
                'name' => 'Áo khoác Denim',
                'description' => 'Áo khoác denim nam/nữ phong cách',
                'price' => 699000,
                'quantity' => 40,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'vay-xep-ly'],
            [
                'category_id' => 1,
                'name' => 'Váy xếp ly',
                'description' => 'Váy xếp ly nữ phong cách trẻ trung',
                'price' => 349000,
                'quantity' => 60,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'tui-tote-canvas'],
            [
                'category_id' => 4,
                'name' => 'Túi tote Canvas',
                'description' => 'Túi tote phong cách casual',
                'price' => 149000,
                'quantity' => 80,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'mu-luoi-trai'],
            [
                'category_id' => 4,
                'name' => 'Mũ lưỡi trai',
                'description' => 'Mũ lưỡi trai thời trang',
                'price' => 99000,
                'quantity' => 120,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'dam-da-hoi'],
            [
                'category_id' => 1,
                'name' => 'Đầm dạ hội',
                'description' => 'Đầm dạ hội sang trọng',
                'price' => 1299000,
                'quantity' => 10,
            ]
        );
    }
}
