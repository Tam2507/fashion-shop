<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterSetting;

class FooterSettingSeeder extends Seeder
{
    public function run()
    {
        FooterSetting::create([
            'company_name' => 'Fashion Shop',
            'company_description' => 'Công ty TNHH Fashion Shop chuyên cung cấp thời trang cao cấp',
            'address' => 'Lầu 1-2, 123 Nguyễn Văn Cừ, Quận 1, TP.HCM',
            'phone' => '0901.234.567',
            'email' => 'info@fashionshop.vn',
            'hotline' => '1900.1234',
            'business_license' => 'Số ĐKKD: 0123456789 do Sở KH&ĐT TP.HCM cấp ngày 01/01/2026',
            'social_facebook' => 'https://facebook.com/fashionshop',
            'social_instagram' => 'https://instagram.com/fashionshop',
            'social_youtube' => 'https://youtube.com/fashionshop',
            'social_tiktok' => 'https://tiktok.com/@fashionshop',
            'payment_methods' => ['visa', 'mastercard', 'jcb', 'atm'],
            'working_hours' => 'Thứ 2 - Thứ 6: 8:00 - 18:00\nThứ 7 - Chủ nhật: 9:00 - 17:00',
            'copyright_text' => '© 2026 Fashion Shop - Thời Trang Cao Cấp. All rights reserved.'
        ]);
    }
}