<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentMethod;

return new class extends Migration
{
    public function up(): void
    {
        // Add COD payment method if not exists
        PaymentMethod::updateOrCreate(
            ['code' => 'cod'],
            [
                'name' => 'Thanh toán khi giao hàng (COD)',
                'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
                'position' => 1,
                'is_active' => true
            ]
        );

        // Update ATM to be active
        PaymentMethod::where('code', 'atm')->update([
            'is_active' => true,
            'position' => 2
        ]);

        // Deactivate other payment methods
        PaymentMethod::whereIn('code', ['visa', 'mastercard', 'jcb', 'momo', 'zalopay'])
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        PaymentMethod::where('code', 'cod')->delete();
    }
};
