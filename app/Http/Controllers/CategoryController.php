<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ADMIN: Danh sách danh mục
    public function index()
    {
        $categories = Category::withCount('products')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    // ADMIN: Tạo danh mục
    public function create()
    {
        return view('admin.categories.create');
    }

    // ADMIN: Lưu danh mục
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = \Str::slug($validated['name']);
        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo');
    }

    // ADMIN: Sửa danh mục
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // ADMIN: Cập nhật danh mục
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật');
    }

    // ADMIN: Xóa danh mục
    public function destroy(string $id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa');
    }

    // PUBLIC: Hiển thị danh mục
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate(12);
        return view('products.category', compact('category', 'products'));
    }
}
