<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with('category')->paginate(12);
        return view('products.index', compact('products'));
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();
        return view('products.show', compact('product', 'relatedProducts'));
    }

    // Hiển thị sản phẩm theo danh mục
    public function byCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $products = $category->products()->paginate(12);
        return view('products.category', compact('category', 'products'));
    }

    // ADMIN: Hiển thị danh sách sản phẩm quản lý
    public function adminIndex()
    {
        $products = Product::with('category')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    // ADMIN: Tạo sản phẩm
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // ADMIN: Lưu sản phẩm
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['slug'] = \Str::slug($validated['name']);
        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo');
    }

    // ADMIN: Sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // ADMIN: Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật');
    }

    // ADMIN: Xóa sản phẩm
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa');
    }
}
