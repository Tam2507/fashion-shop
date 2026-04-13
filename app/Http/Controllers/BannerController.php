<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
            'position' => 'required|integer|min:0',
            'banner_type' => 'required|in:hero,promotion,announcement',
            'page' => 'required|in:home,products,all',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = (new ImageUploadService)->upload($request->file('image'), 'banners');
        }

        // Set default values
        $validated['subtitle'] = null;
        $validated['description'] = null;
        $validated['background_color'] = '#8B3A3A';
        $validated['text_color'] = '#FFFFFF';
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Banner::create($validated);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được tạo thành công!');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'link_url' => 'nullable|string|max:500',
                'link_text' => 'nullable|string|max:100',
                'position' => 'required|integer|min:0',
                'banner_type' => 'required|in:hero,promotion,announcement',
                'page' => 'required|in:home,products,all',
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $svc = new ImageUploadService;
                $svc->delete($banner->image);
                $banner->image = $svc->upload($file, 'banners');
            }

            // Update other fields
            $banner->title = $validated['title'];
            $banner->link_url = $validated['link_url'] ?? null;
            $banner->link_text = $validated['link_text'] ?? null;
            $banner->position = $validated['position'];
            $banner->banner_type = $validated['banner_type'];
            $banner->page = $validated['page'];
            $banner->is_active = $request->has('is_active') ? 1 : 0;
            
            $banner->save();

            return redirect()->route('admin.banners.index')
                ->with('success', 'Banner đã được cập nhật thành công!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy(Banner $banner)
    {
        (new ImageUploadService)->delete($banner->image);
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được xóa thành công!');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        
        $status = $banner->is_active ? 'kích hoạt' : 'tạm dừng';
        return response()->json([
            'success' => true,
            'message' => "Banner đã được {$status}",
            'is_active' => $banner->is_active
        ]);
    }
}