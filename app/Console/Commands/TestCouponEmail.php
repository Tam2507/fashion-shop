<?php

namespace App\Console\Commands;

use App\Mail\CouponRewardMail;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestCouponEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:test-email {email : Email của user để test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test gửi email mã giảm giá cho một user cụ thể';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Không tìm thấy user với email: {$email}");
            return 1;
        }

        $this->info("👤 Tìm thấy user: {$user->name}");
        
        // Tính tổng chi tiêu
        $totalSpent = $user->orders()
            ->whereIn('status', ['delivered', 'confirmed'])
            ->sum('total_price');
        
        $this->info("💰 Tổng chi tiêu: " . number_format($totalSpent, 0, ',', '.') . "đ");
        
        // Tạo mã test
        $couponCode = 'TEST-' . $user->id . '-' . strtoupper(Str::random(6));
        
        $coupon = Coupon::create([
            'code' => $couponCode,
            'type' => 'percentage',
            'value' => 10,
            'minimum_amount' => 0,
            'maximum_discount' => 100000,
            'usage_limit' => 1,
            'used_count' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ]);
        
        $this->info("🎟️  Đã tạo mã test: {$couponCode}");
        
        try {
            Mail::to($user->email)->send(new CouponRewardMail($user, $coupon, $totalSpent));
            $this->info("✅ Đã gửi email test thành công đến: {$user->email}");
            $this->info("📧 Vui lòng kiểm tra hộp thư của bạn!");
        } catch (\Exception $e) {
            $this->error("❌ Lỗi gửi email: " . $e->getMessage());
            $coupon->delete();
            return 1;
        }

        return 0;
    }
}
