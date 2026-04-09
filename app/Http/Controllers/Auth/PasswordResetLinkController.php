<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    // Bước 1: Form nhập email
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    // Bước 2: Kiểm tra email tồn tại, chuyển sang form đổi mật khẩu
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email này không tồn tại trong hệ thống.'])->withInput();
        }

        // Lưu email vào session rồi chuyển sang form đổi mật khẩu
        session(['reset_email' => $request->email]);

        return redirect()->route('password.reset.form');
    }
}
