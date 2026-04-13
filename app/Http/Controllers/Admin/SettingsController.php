<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
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
