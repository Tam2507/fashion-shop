<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run()
    {
        // Banner cho trang chủ
        Banner::create([
            'title' => 'Tết Đến Sale Về',
            'subtitle' => 'Khuyến mãi lớn nhất năm 2026',
            'description' => 'Giảm giá lên đến 50% + 20% khi mua từ 2 sản phẩm',
            'link_url' => '/products',
            'link_text' => 'MUA NGAY',
            'position' => 1,
            'is_active' => true,
            'background_color' => '#8B0000',
            'text_color' => '#FFFFFF',
            'banner_type' => 'hero',
            'page' => 'home'
        ]);

        Banner::create([
            'title' => 'Chạm Mode',
            'subtitle' => 'Khám phá Bộ Sưu Tập Thời Trang Cao Cấp 2026',
            'description' => 'Xu hướng thời trang mới nhất, phong cách hiện đại',
            'link_url' => '/products',
            'link_text' => 'KHÁM PHÁ NGAY',
            'position' => 2,
            'is_active' => true,
            'background_color' => '#8B3A3A',
            'text_color' => '#FFFFFF',
            'banner_type' => 'hero',
            'page' => 'home'
        ]);

        // Banner cho trang sản phẩm
        Banner::create([
            'title' => 'Bộ Sưu Tập Mới 2026',
            'subtitle' => 'Thời trang công sở thanh lịch',
            'description' => 'Phong cách chuyên nghiệp, sang trọng cho phái đẹp',
            'link_url' => '/products',
            'link_text' => 'XEM NGAY',
            'position' => 1,
            'is_active' => true,
            'background_color' => '#2C3E50',
            'text_color' => '#FFFFFF',
            'banner_type' => 'hero',
            'page' => 'products'
        ]);

        Banner::create([
            'title' => 'Sale Cuối Năm',
            'subtitle' => 'Giảm giá đến 70% toàn bộ sản phẩm',
            'description' => 'Cơ hội mua sắm với giá tốt nhất trong năm',
            'link_url' => '/products',
            'link_text' => 'MUA NGAY',
            'position' => 2,
            'is_active' => true,
            'background_color' => '#C41E3A',
            'text_color' => '#FFFFFF',
            'banner_type' => 'promotion',
            'page' => 'products'
        ]);

        // Banner cho tất cả trang
        Banner::create([
            'title' => 'Fashion Shop',
            'subtitle' => 'Thời trang cao cấp cho mọi phong cách',
            'description' => 'Khám phá bộ sưu tập độc đáo và sang trọng',
            'link_url' => '/products',
            'link_text' => 'KHÁM PHÁ',
            'position' => 3,
            'is_active' => true,
            'background_color' => '#8B3A3A',
            'text_color' => '#FFFFFF',
            'banner_type' => 'hero',
            'page' => 'all'
        ]);
    }
}