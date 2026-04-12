<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function momoSettings()
    {
        try {
            return view('admin.settings.momo');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function sepaySettings()
    {
        $ipnUrl = url('/payment/sepay/ipn');
        return view('admin.settings.sepay', compact('ipnUrl'));
    }

    public function updateSepaySettings(Request $request)
    {
        $request->validate([
            'merchant_id' => 'nullable|string|max:255',
            'api_key'     => 'nullable|string|max:255',
            'env'         => 'required|in:sandbox,production',
        ]);

        if ($request->filled('merchant_id')) {
            $this->updateEnvFile('SEPAY_MERCHANT_ID', $request->merchant_id);
        }
        if ($request->filled('api_key')) {
            $this->updateEnvFile('SEPAY_API_KEY', $request->api_key);
        }
        $this->updateEnvFile('SEPAY_ENV', $request->env);

        return redirect()->back()->with('success', 'Đã cập nhật cấu hình SePay thành công!');
    }

    public function uploadMomoQR(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Upload QR image
        if ($request->hasFile('qr_image')) {
            $file = $request->file('qr_image');
            $filename = 'momo-qr.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/settings', $filename);
            
            $qrUrl = asset('storage/settings/' . $filename);
            
            // Update .env file
            $this->updateEnvFile('MOMO_STATIC_QR', $qrUrl);
            
            if ($request->filled('momo_phone')) {
                $this->updateEnvFile('MOMO_PHONE', $request->momo_phone);
            }
            
            if ($request->filled('momo_name')) {
                $this->updateEnvFile('MOMO_NAME', $request->momo_name);
            }
            
            return redirect()->back()->with('success', 'Đã cập nhật QR MoMo thành công!');
        }

        return redirect()->back()->with('error', 'Không thể upload ảnh');
    }

    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Check if key exists
            if (strpos($content, $key . '=') !== false) {
                // Update existing key
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $content
                );
            } else {
                // Add new key
                $content .= "\n{$key}={$value}";
            }
            
            file_put_contents($path, $content);
        }
    }
}
