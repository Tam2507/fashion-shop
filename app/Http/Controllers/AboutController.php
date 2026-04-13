<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    // Public: Show about page
    public function index()
    {
        $about = AboutPage::first();
        return view('pages.about', compact('about'));
    }

    // Admin: Edit about page
    public function adminEdit()
    {
        $about = AboutPage::first();
        
        // Create default if not exists
        if (!$about) {
            $about = AboutPage::create([
                'title' => 'Về Fashion Shop',
                'intro' => 'Fashion Shop được thành lập với sứ mệnh mang đến những sản phẩm thời trang cao cấp, phù hợp với phong cách hiện đại của người Việt Nam.',
                'vision' => 'Trở thành thương hiệu thời trang hàng đầu Việt Nam, được khách hàng tin tưởng và yêu thích.',
                'mission' => 'Cung cấp những sản phẩm thời trang chất lượng cao với giá cả hợp lý, giúp khách hàng tự tin thể hiện phong cách cá nhân.',
                'core_values' => "Chất lượng: Cam kết chất lượng sản phẩm tốt nhất\nDịch vụ: Phục vụ khách hàng tận tâm, chu đáo\nUy tín: Xây dựng niềm tin với khách hàng\nĐổi mới: Luôn cập nhật xu hướng thời trang mới",
            ]);
        }
        
        return view('admin.about.edit', compact('about'));
    }

    // Admin: Update about page
    public function adminUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'intro' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'core_values' => 'nullable|string',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $about = AboutPage::first();
        
        // Create if not exists
        if (!$about) {
            $about = new AboutPage();
        }
        
        $data = $request->except(['image_1', 'image_2', 'image_3']);

        for ($i = 1; $i <= 3; $i++) {
            $fieldName = "image_$i";
            if ($request->hasFile($fieldName)) {
                $svc = new ImageUploadService;
                $svc->delete($about->$fieldName);
                $data[$fieldName] = $svc->upload($request->file($fieldName), 'about');
            }
        }

        if ($about->exists) {
            $about->update($data);
        } else {
            $about->fill($data);
            $about->save();
        }

        return redirect()->back()->with('success', 'Đã cập nhật trang Về Chúng Tôi!');
    }

    // Admin: Delete image
    public function adminDeleteImage(Request $request, $imageNumber)
    {
        $about = AboutPage::first();
        $fieldName = "image_$imageNumber";
        if ($about->$fieldName) {
            (new ImageUploadService)->delete($about->$fieldName);
            $about->update([$fieldName => null]);
        }

        return redirect()->back()->with('success', 'Đã xóa ảnh!');
    }
}
