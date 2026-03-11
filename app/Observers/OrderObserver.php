<?php

namespace App\Observers;

use App\Mail\CouponRewardMail;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Chỉ xử lý khi đơn hàng chuyển sang trạng thái delivered
        if ($order->isDirty('status') && $order->status === 'delivered') {
            $this->checkAndSendRewardCoupon($order);
        }
    }

    /**
     * Kiểm tra và gửi mã giảm giá reward nếu đủ điều kiện
     */
    private function checkAndSendRewardCoupon(Order $order): void
    {
        // Chỉ xử lý cho user đã đăng nhập
        if (!$order->user_id) {
            return;
        }

        $user = $order->user;
        
        // Tính tổng chi tiêu của user (chỉ đơn đã giao)
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->sum('total_price');

        // Ngưỡng 500k
        $threshold = 500000;

        // Kiểm tra đã đạt ngưỡng chưa
        if ($totalSpent < $threshold) {
            return;
        }

        // Kiểm tra xem đã gửi mã reward trong 90 ngày gần đây chưa
        $recentReward = Coupon::where('code', 'LIKE', 'REWARD-' . $user->id . '-%')
            ->where('created_at', '>=', now()->subDays(90))
            ->exists();

        if ($recentReward) {
            return; // Đã gửi rồi, không gửi lại
        }

        // Tạo mã giảm giá unique
        $couponCode = 'REWARD-' . $user->id . '-' . strtoupper(Str::random(6));
        
        try {
            // Tạo coupon mới
            $coupon = Coupon::create([
                'code' => $couponCode,
                'type' => 'percentage',
                'value' => 10, // Giảm 10%
                'minimum_amount' => 0,
                'maximum_discount' => 100000, // Giảm tối đa 100k
                'usage_limit' => 1,
                'used_count' => 0,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30), // Hiệu lực 30 ngày
                'is_active' => true,
            ]);

            // Gửi email thông báo
            Mail::to($user->email)->send(new CouponRewardMail($user, $coupon, $totalSpent));
            
            \Log::info("Sent reward coupon {$couponCode} to user {$user->email} (total spent: {$totalSpent})");
        } catch (\Exception $e) {
            \Log::error("Failed to send reward coupon to user {$user->email}: " . $e->getMessage());
        }
    }
}
