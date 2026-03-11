<?php

namespace App\Http\Controllers;

use App\Mail\CouponNotificationMail;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'send_notification' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['used_count'] = 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $coupon = Coupon::create($validated);

        // Gửi thông báo đến tất cả khách hàng nếu được chọn
        if ($request->has('send_notification') && $request->send_notification) {
            $this->sendCouponNotification($coupon);
        }

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã tạo mã giảm giá thành công!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed_amount,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã cập nhật mã giảm giá thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã xóa mã giảm giá thành công!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
            'message' => $coupon->is_active ? 'Đã kích hoạt mã giảm giá' : 'Đã vô hiệu hóa mã giảm giá'
        ]);
    }

    public function sendNotification(Coupon $coupon)
    {
        $count = $this->sendCouponNotification($coupon);
        
        return redirect()->route('admin.coupons.index')
            ->with('success', "Đã gửi thông báo đến {$count} khách hàng!");
    }

    private function sendCouponNotification(Coupon $coupon)
    {
        $users = User::where('is_admin', 0)->get();
        $count = 0;

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new CouponNotificationMail($user, $coupon));
                $count++;
            } catch (\Exception $e) {
                \Log::error("Failed to send coupon notification to {$user->email}: " . $e->getMessage());
            }
        }

        return $count;
    }
}
