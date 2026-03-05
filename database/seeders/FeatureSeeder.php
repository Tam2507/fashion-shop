<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        $features = [
            [
                'title' => 'Giao Hàng Nhanh',
                'description' => 'Giao hàng trong 24h tại TP.HCM',
                'icon' => 'fas fa-shipping-fast',
                'position' => 1,
                'is_active' => true,
                'background_color' => '#8B3A3A',
                'icon_color' => '#FFFFFF'
            ],
            [
                'title' => 'Đổi Trả Dễ Dàng',
                'description' => 'Đổi trả trong 30 ngày',
                'icon' => 'fas fa-undo-alt',
                'position' => 2,
                'is_active' => true,
                'background_color' => '#8B3A3A',
                'icon_color' => '#FFFFFF'
            ],
            [
                'title' => 'Bảo Hành Chất Lượng',
                'description' => 'Cam kết chất lượng 100%',
                'icon' => 'fas fa-shield-alt',
                'position' => 3,
                'is_active' => true,
                'background_color' => '#8B3A3A',
                'icon_color' => '#FFFFFF'
            ],
            [
                'title' => 'Hỗ Trợ 24/7',
                'description' => 'Tư vấn miễn phí mọi lúc',
                'icon' => 'fas fa-headset',
                'position' => 4,
                'is_active' => true,
                'background_color' => '#8B3A3A',
                'icon_color' => '#FFFFFF'
            ]
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}