<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('position')->paginate(10);
        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:100',
            'position' => 'required|integer|min:0',
            'background_color' => 'required|string|max:7',
            'icon_color' => 'required|string|max:7',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Feature::create($validated);

        return redirect()->route('admin.features.index')
            ->with('success', 'Tính năng đã được tạo thành công!');
    }

    public function show(Feature $feature)
    {
        return view('admin.features.show', compact('feature'));
    }

    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:100',
            'position' => 'required|integer|min:0',
            'background_color' => 'required|string|max:7',
            'icon_color' => 'required|string|max:7',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $feature->update($validated);

        return redirect()->route('admin.features.index')
            ->with('success', 'Tính năng đã được cập nhật thành công!');
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();
        
        return redirect()->route('admin.features.index')
            ->with('success', 'Tính năng đã được xóa thành công!');
    }

    public function toggleStatus(Feature $feature)
    {
        $feature->update(['is_active' => !$feature->is_active]);
        
        $status = $feature->is_active ? 'kích hoạt' : 'tạm dừng';
        return response()->json([
            'success' => true,
            'message' => "Tính năng đã được {$status}",
            'is_active' => $feature->is_active
        ]);
    }
}