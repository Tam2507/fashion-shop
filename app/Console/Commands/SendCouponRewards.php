<?php

namespace App\Console\Commands;

use App\Mail\CouponRewardMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendCouponRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:send-rewards 
                            {--threshold=500000 : Ngưỡng chi tiêu tối thiểu}
                            {--discount=10 : Phần trăm giảm giá}
                            {--days=30 : Số ngày hiệu lực của mã}
                            {--dry-run : Chạy thử không gửi email thật}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động gửi mã giảm giá cho khách hàng có lịch sử mua hàng đạt ngưỡng';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = (float) $this->option('threshold');
        $discountPercent = (int) $this->option('discount');
        $validDays = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $this->info("🎯 Bắt đầu kiểm tra khách hàng đủ điều kiện...");
        $this->info("   Ngưỡng chi tiêu: " . number_format($threshold, 0, ',', '.') . "đ");
        $this->info("   Giảm giá: {$discountPercent}%");
        $this->info("   Hiệu lực: {$validDays} ngày");
        
        if ($dryRun) {
            $this->warn("   ⚠️  CHẾ ĐỘ THỬ NGHIỆM - Không gửi email thật");
        }
        
        $this->newLine();

        // Lấy danh sách khách hàng có tổng chi tiêu >= threshold
        // và chưa nhận mã giảm giá reward trong 90 ngày gần đây
        $eligibleUsers = User::select('users.*')
            ->selectRaw('SUM(orders.total_price) as total_spent')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereIn('orders.status', ['delivered', 'confirmed'])
            ->groupBy('users.id')
            ->havingRaw('SUM(orders.total_price) >= ?', [$threshold])
            ->get();

        if ($eligibleUsers->isEmpty()) {
            $this->warn("❌ Không tìm thấy khách hàng nào đủ điều kiện.");
            return 0;
        }

        $this->info("✅ Tìm thấy {$eligibleUsers->count()} khách hàng đủ điều kiện:");
        $this->newLine();

        $sentCount = 0;
        $skippedCount = 0;

        foreach ($eligibleUsers as $user) {
            // Kiểm tra xem user đã nhận mã reward trong 90 ngày gần đây chưa
            $recentReward = Coupon::where('code', 'LIKE', 'REWARD-' . $user->id . '-%')
                ->where('created_at', '>=', now()->subDays(90))
                ->exists();

            if ($recentReward) {
                $this->line("   ⏭️  Bỏ qua: {$user->name} ({$user->email}) - Đã nhận mã trong 90 ngày gần đây");
                $skippedCount++;
                continue;
            }

            // Tạo mã giảm giá unique
            $couponCode = 'REWARD-' . $user->id . '-' . strtoupper(Str::random(6));
            
            // Tạo coupon mới
            $coupon = Coupon::create([
                'code' => $couponCode,
                'type' => 'percentage',
                'value' => $discountPercent,
                'minimum_amount' => 0,
                'maximum_discount' => 100000, // Giảm tối đa 100k
                'usage_limit' => 1,
                'used_count' => 0,
                'starts_at' => now(),
                'expires_at' => now()->addDays($validDays),
                'is_active' => true,
            ]);

            $totalSpent = $user->total_spent;

            $this->line("   👤 {$user->name} ({$user->email})");
            $this->line("      💰 Tổng chi tiêu: " . number_format($totalSpent, 0, ',', '.') . "đ");
            $this->line("      🎟️  Mã: {$couponCode}");

            // Gửi email
            if (!$dryRun) {
                try {
                    Mail::to($user->email)->send(new CouponRewardMail($user, $coupon, $totalSpent));
                    $this->info("      ✅ Đã gửi email thành công!");
                    $sentCount++;
                } catch (\Exception $e) {
                    $this->error("      ❌ Lỗi gửi email: " . $e->getMessage());
                    // Xóa coupon nếu gửi email thất bại
                    $coupon->delete();
                }
            } else {
                $this->info("      ℹ️  [DRY RUN] Email sẽ được gửi đến: {$user->email}");
                $sentCount++;
                // Xóa coupon trong chế độ dry-run
                $coupon->delete();
            }

            $this->newLine();
        }

        $this->newLine();
        $this->info("📊 Tổng kết:");
        $this->info("   ✅ Đã gửi: {$sentCount}");
        $this->info("   ⏭️  Bỏ qua: {$skippedCount}");
        $this->info("   📧 Tổng cộng: " . ($sentCount + $skippedCount));

        return 0;
    }
}
