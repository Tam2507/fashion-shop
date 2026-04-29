<?php

namespace App\Observers;

use App\Models\Coupon;
use App\Models\Order;
use App\Services\BrevoMailService;
use Illuminate\Support\Str;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === 'delivered') {
            $this->checkAndSendRewardCoupon($order);
        }
    }

    private function checkAndSendRewardCoupon(Order $order): void
    {
        if (!$order->user_id) return;

        $user = $order->user;
        $totalSpent = Order::where('user_id', $user->id)->where('status', 'delivered')->sum('total_price');

        if ($totalSpent < 500000) return;

        $recentReward = Coupon::where('code', 'LIKE', 'REWARD-' . $user->id . '-%')
            ->where('created_at', '>=', now()->subDays(90))
            ->exists();

        if ($recentReward) return;

        $couponCode = 'REWARD-' . $user->id . '-' . strtoupper(Str::random(6));

        try {
            $coupon = Coupon::create([
                'code'             => $couponCode,
                'type'             => 'percentage',
                'value'            => 10,
                'minimum_amount'   => 0,
                'maximum_discount' => 100000,
                'usage_limit'      => 1,
                'used_count'       => 0,
                'starts_at'        => now(),
                'expires_at'       => now()->addDays(30),
                'is_active'        => true,
            ]);

            $html = "<p>Xin chào <strong>{$user->name}</strong>,</p>
                     <p>Cảm ơn bạn đã mua sắm tại Fashion Shop! Bạn đã chi tiêu tổng cộng <strong>" . number_format($totalSpent, 0, ',', '.') . "₫</strong>.</p>
                     <p>Chúng tôi tặng bạn mã giảm giá <strong>{$couponCode}</strong> — giảm 10% (tối đa 100.000₫), hiệu lực 30 ngày.</p>";

            (new BrevoMailService())->send($user->email, $user->name, 'Phần thưởng khách hàng thân thiết!', $html);

            \Log::info("Sent reward coupon {$couponCode} to {$user->email}");
        } catch (\Exception $e) {
            \Log::error("Failed to send reward coupon: " . $e->getMessage());
        }
    }
}
