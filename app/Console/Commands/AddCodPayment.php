<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentMethod;

class AddCodPayment extends Command
{
    protected $signature = 'payment:add-cod';
    protected $description = 'Add COD payment method';

    public function handle()
    {
        $cod = PaymentMethod::updateOrCreate(
            ['code' => 'cod'],
            [
                'name' => 'Thanh toán khi giao hàng (COD)',
                'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
                'position' => 1,
                'is_active' => true
            ]
        );

        $this->info('✓ COD payment method added successfully!');
        $this->info('Name: ' . $cod->name);
        $this->info('Code: ' . $cod->code);
        $this->info('Status: ' . ($cod->is_active ? 'Active' : 'Inactive'));

        return 0;
    }
}
