<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    // Bước 3: Form đổi mật khẩu mới
    public function create(Request $request): View|RedirectResponse
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.']);
        }

        return view('auth.reset-password', ['email' => session('reset_email')]);
    }

    // Bước 4: Lưu mật khẩu mới
    public function store(Request $request): RedirectResponse
    {
        $email = session('reset_email');

        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);

        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Xóa OTP khỏi DB
        \Illuminate\Support\Facades\DB::table('password_reset_otps')->where('email', $email)->delete();

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('status', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
