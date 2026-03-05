<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $paymentMethods = [
            [
                'name' => 'Thanh toán khi giao hàng (COD)',
                'code' => 'cod',
                'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
                'position' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Thẻ ATM nội địa',
                'code' => 'atm',
                'description' => 'Thanh toán qua thẻ ATM/Internet Banking',
                'position' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Visa',
                'code' => 'visa',
                'description' => 'Thẻ tín dụng/ghi nợ Visa',
                'position' => 3,
                'is_active' => false
            ],
            [
                'name' => 'MasterCard',
                'code' => 'mastercard',
                'description' => 'Thẻ tín dụng/ghi nợ MasterCard',
                'position' => 4,
                'is_active' => false
            ],
            [
                'name' => 'JCB',
                'code' => 'jcb',
                'description' => 'Thẻ tín dụng JCB',
                'position' => 5,
                'is_active' => false
            ],
            [
                'name' => 'MoMo',
                'code' => 'momo',
                'description' => 'Ví điện tử MoMo',
                'position' => 6,
                'is_active' => false
            ],
            [
                'name' => 'ZaloPay',
                'code' => 'zalopay',
                'description' => 'Ví điện tử ZaloPay',
                'position' => 7,
                'is_active' => false
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
