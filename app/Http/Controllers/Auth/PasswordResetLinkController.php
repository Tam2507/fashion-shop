<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    // Bước 1: Form nhập email
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    // Bước 2: Gửi OTP về email
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email này không tồn tại trong hệ thống.'])->withInput();
        }

        // Tạo OTP 6 số
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Lưu vào DB (upsert)
        DB::table('password_reset_otps')->upsert([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ], ['email'], ['otp', 'expires_at', 'updated_at']);

        // Gửi mail qua queue (bất đồng bộ)
        Mail::to($request->email)->queue(new PasswordResetOtpMail($otp));

        // Lưu email vào session để dùng ở bước sau
        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp.form')
            ->with('status', 'Mã xác nhận đã được gửi đến email của bạn.');
    }

    // Bước 3: Form nhập OTP
    public function otpForm(): View|RedirectResponse
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp', ['email' => session('reset_email')]);
    }

    // Bước 4: Xác minh OTP
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Vui lòng nhập mã xác nhận.',
            'otp.digits'   => 'Mã xác nhận phải gồm 6 chữ số.',
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Mã xác nhận không đúng.']);
        }

        if (now()->isAfter($record->expires_at)) {
            return back()->withErrors(['otp' => 'Mã xác nhận đã hết hạn. Vui lòng yêu cầu mã mới.']);
        }

        // OTP hợp lệ — cho phép đổi mật khẩu
        session(['otp_verified' => true]);

        return redirect()->route('password.reset.form');
    }
}
