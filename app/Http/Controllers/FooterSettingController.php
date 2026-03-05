<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;
use Illuminate\Http\Request;

class FooterSettingController extends Controller
{
    public function index()
    {
        $settings = FooterSetting::getSettings();
        return view('admin.footer-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'hotline' => 'nullable|string|max:20',
            'business_license' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'social_tiktok' => 'nullable|url',
            'payment_methods' => 'nullable|array',
            'working_hours' => 'nullable|string',
            'copyright_text' => 'nullable|string'
        ]);

        $settings = FooterSetting::first();
        
        if ($settings) {
            $settings->update($validated);
        } else {
            FooterSetting::create($validated);
        }

        return redirect()->route('admin.footer-settings.index')
            ->with('success', 'Cài đặt footer đã được cập nhật thành công!');
    }
}