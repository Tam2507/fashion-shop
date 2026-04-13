<?php

namespace App\Http\Controllers;

use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'avatar.max' => 'Ảnh không được vượt quá 5MB',
            'avatar.image' => 'File phải là ảnh',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, webp',
        ]);

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('avatar')) {
            $svc = new ImageUploadService;
            $svc->delete($user->avatar);
            $user->avatar = $svc->upload($request->file('avatar'), 'avatars');
        }

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        if ($request->hasFile('avatar')) {
            return redirect()->back()->with('success', 'Đã cập nhật ảnh đại diện!');
        }

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công!');
    }

    public function deleteAvatar()
    {
        $user = auth()->user();

        (new ImageUploadService)->delete($user->avatar);
        $user->avatar = null;
        $user->save();

        return redirect()->back()->with('success', 'Đã xóa ảnh đại diện!');
    }
}
