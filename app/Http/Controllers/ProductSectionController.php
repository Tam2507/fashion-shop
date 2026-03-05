<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductSectionController extends Controller
{
    public function index()
    {
        $sections = ProductSection::withCount('products')->orderBy('display_order')->get();
        return view('admin.product-sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.product-sections.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:product_sections,slug',
                'description' => 'nullable|string',
                'max_products' => 'required|integer|min:1|max:50',
                'display_order' => 'required|integer|min:0',
                'is_active' => 'boolean',
            ]);

            $data = $request->all();
            $data['slug'] = $request->slug ?: Str::slug($request->name);
            $data['is_active'] = $request->has('is_active');

            $section = ProductSection::create($data);
            
            \Log::info('Section created:', [
                'id' => $section->id,
                'name' => $section->name,
                'is_active' => $section->is_active,
                'data' => $data
            ]);

            return redirect()->route('admin.product-sections.index')
                ->with('success', 'Đã tạo section "' . $section->name . '" thành công! ID: ' . $section->id);
        } catch (\Exception $e) {
            \Log::error('Error creating section:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi khi tạo section: ' . $e->getMessage());
        }
    }

    public function edit(ProductSection $productSection)
    {
        $products = Product::orderBy('name')->get();
        $sectionProducts = $productSection->products()->get();
        
        return view('admin.product-sections.edit', compact('productSection', 'products', 'sectionProducts'));
    }

    public function update(Request $request, ProductSection $productSection)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:product_sections,slug,' . $productSection->id,
            'description' => 'nullable|string',
            'max_products' => 'required|integer|min:1|max:50',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        $productSection->update($data);

        return redirect()->route('admin.product-sections.index')
            ->with('success', 'Đã cập nhật section thành công!');
    }

    public function destroy(ProductSection $productSection)
    {
        $productSection->delete();
        return redirect()->route('admin.product-sections.index')
            ->with('success', 'Đã xóa section thành công!');
    }

    public function manageProducts(Request $request, ProductSection $productSection)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        $syncData = [];
        foreach ($request->products as $index => $productId) {
            $syncData[$productId] = ['display_order' => $index];
        }

        $productSection->products()->sync($syncData);

        return redirect()->route('admin.product-sections.edit', $productSection)
            ->with('success', 'Đã cập nhật sản phẩm thành công!');
    }
}
