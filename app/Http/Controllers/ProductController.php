<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm (Frontend)
    public function index(Request $request)
    {
        $query = Product::with('category', 'variants', 'images', 'approvedReviews')->where('is_active', true);

        // Search by name or description
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($qbuilder) use ($q) {
                $qbuilder->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'message' => 'Query too short'
            ]);
        }

        $products = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'first_image' => $product->images->first()->image_path ?? null,
                    'category_name' => $product->category->name ?? null,
                ];
            });

        return response()->json([
            'products' => $products,
            'count' => $products->count()
        ]);
    }



    // Hiển thị chi tiết sản phẩm (Frontend)
    public function show(string $id)
    {
        $product = Product::with(['category', 'variants', 'images', 'approvedReviews.user'])
            ->findOrFail($id);
        
        $availableColors = $product->getAvailableColors();
        $availableSizes = $product->getAvailableSizes();
        
        // Check if product is accessory
        $isAccessory = $product->category && 
                       (stripos($product->category->name, 'phụ kiện') !== false || 
                        stripos($product->category->name, 'accessory') !== false ||
                        stripos($product->category->name, 'accessories') !== false);
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->limit(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts', 'availableColors', 'availableSizes', 'isAccessory'));
    }

    // Hiển thị sản phẩm theo danh mục (Frontend)
    public function byCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $products = $category->products()->where('is_active', true)->paginate(12);
        return view('products.category', compact('category', 'products'));
    }

    // ADMIN: Hiển thị danh sách sản phẩm quản lý
    public function adminIndex(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            switch($status) {
                case 'in_stock':
                    $query->whereHas('variants', function($q) {
                        $q->where('stock_quantity', '>', 0);
                    })->orWhere('quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->whereDoesntHave('variants', function($q) {
                        $q->where('stock_quantity', '>', 0);
                    })->where('quantity', '<=', 0);
                    break;
                case 'low_stock':
                    $query->whereHas('variants', function($q) {
                        $q->whereRaw('stock_quantity <= 5 AND stock_quantity > 0');
                    })->orWhereBetween('quantity', [1, 5]);
                    break;
            }
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.products.index', compact('products'));
    }

    // ADMIN: Tạo sản phẩm mới
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // ADMIN: Lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*.file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*.color' => 'required|string|max:50',
            'images.*.sizes' => 'nullable|array',
            'confirm_duplicate' => 'nullable|boolean',
        ]);

        // Check for duplicate product name
        $baseSlug = Str::slug($validated['name']);
        $existingProduct = Product::where('slug', $baseSlug)->first();
        
        if ($existingProduct && !$request->input('confirm_duplicate')) {
            return redirect()->back()
                ->withInput()
                ->with('warning', "Đã có sản phẩm tên '{$existingProduct->name}' trong hệ thống. Bạn có chắc muốn tạo sản phẩm trùng tên?")
                ->with('show_duplicate_confirm', true);
        }

        // Generate unique slug
        $slug = $baseSlug;
        $counter = 1;
        
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->input('action') === 'publish';
        
        // Set SEO defaults
        if (empty($validated['seo_title'])) {
            $validated['seo_title'] = $validated['name'];
        }
        if (empty($validated['seo_description'])) {
            $validated['seo_description'] = Str::limit($validated['description'], 160);
        }

        // Create product without main image (will use first uploaded image)
        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'is_active' => $validated['is_active'],
            'seo_title' => $validated['seo_title'],
            'seo_description' => $validated['seo_description'],
            'meta_keywords' => $validated['meta_keywords'],
        ]);

        // Process images and create variants
        if ($request->has('images')) {
            $firstImage = true;
            
            foreach ($request->input('images') as $index => $imageData) {
                // Upload image
                if ($request->hasFile("images.{$index}.file")) {
                    $imagePath = $request->file("images.{$index}.file")->store('products', 'public');
                    
                    // Set first image as main product image
                    if ($firstImage) {
                        $product->update(['image' => $imagePath]);
                        $firstImage = false;
                    }
                    
                    // Create ProductImage record
                    $productImage = ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $imagePath,
                        'color' => $imageData['color'],
                        'position' => $index + 1,
                    ]);
                    
                    // Create variants for each size
                    if (isset($imageData['sizes']) && is_array($imageData['sizes'])) {
                        foreach ($imageData['sizes'] as $size => $quantity) {
                            // Generate SKU
                            $colorSlug = strtoupper(substr(Str::slug($imageData['color']), 0, 3));
                            $sku = "PRD{$product->id}-{$colorSlug}-{$size}";
                            
                            // Create variant
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $sku,
                                'size' => $size,
                                'color' => $imageData['color'],
                                'price' => $product->price,
                                'stock_quantity' => (int) $quantity,
                            ]);
                        }
                    }
                }
            }
        }

        // Index product for search
        if (app()->bound(\App\Contracts\SearchEngineInterface::class)) {
            app(\App\Contracts\SearchEngineInterface::class)->indexProduct($product);
        }

        // Đồng bộ quantity từ variants
        $product->syncQuantity();

        $message = $validated['is_active'] ? 'Sản phẩm đã được xuất bản thành công!' : 'Sản phẩm đã được lưu nháp!';
        return redirect()->route('admin.products.index')->with('success', $message);
    }

    // ADMIN: Sửa sản phẩm
    public function edit(string $id)
    {
        $product = Product::with(['category', 'variants', 'images'])->findOrFail($id);
        $categories = Category::all();
        $availableColors = $product->getAvailableColors();
        
        // Use unified edit view with tabs
        return view('admin.products.edit', compact('product', 'categories', 'availableColors'));
    }

    // ADMIN: Cập nhật sản phẩm
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update slug if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        // Handle additional images removal
        if ($request->filled('remove_images')) {
            $imagesToRemove = $request->input('remove_images');
            foreach ($imagesToRemove as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $product->id) {
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    $image->delete();
                }
            }
        }

        // Handle new additional images
        if ($request->hasFile('images')) {
            $maxPosition = $product->images()->max('position') ?? 0;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'position' => $maxPosition + $index + 1,
                ]);
            }
        }

        // Update search index
        if (app()->bound(\App\Contracts\SearchEngineInterface::class)) {
            app(\App\Contracts\SearchEngineInterface::class)->indexProduct($product);
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    // ADMIN: Xóa sản phẩm
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Delete images
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }

        // Remove from search index
        if (app()->bound(\App\Contracts\SearchEngineInterface::class)) {
            app(\App\Contracts\SearchEngineInterface::class)->removeFromIndex($product->id);
        }

        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    // ADMIN: Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $productIds = $request->input('product_ids');
        $action = $request->input('action');
        $count = 0;

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $count = count($productIds);
                $message = "Đã kích hoạt {$count} sản phẩm";
                break;

            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $count = count($productIds);
                $message = "Đã tạm dừng {$count} sản phẩm";
                break;

            case 'delete':
                $products = Product::whereIn('id', $productIds)->get();
                foreach ($products as $product) {
                    // Delete images
                    if ($product->image && Storage::disk('public')->exists($product->image)) {
                        Storage::disk('public')->delete($product->image);
                    }
                    foreach ($product->images as $image) {
                        if (Storage::disk('public')->exists($image->path)) {
                            Storage::disk('public')->delete($image->path);
                        }
                    }
                    // Remove from search index
                    if (app()->bound(\App\Contracts\SearchEngineInterface::class)) {
                        app(\App\Contracts\SearchEngineInterface::class)->removeFromIndex($product->id);
                    }
                }
                Product::whereIn('id', $productIds)->delete();
                $count = count($productIds);
                $message = "Đã xóa {$count} sản phẩm";
                break;
        }

        return redirect()->route('admin.products.index')->with('success', $message);
    }

    // ADMIN: Manage product images
    public function manageImages(string $id)
    {
        $product = Product::with('images', 'variants')->findOrFail($id);
        $availableColors = $product->getAvailableColors();
        return view('admin.products.images', compact('product', 'availableColors'));
    }

    // ADMIN: Assign color to image
    public function assignColor(Request $request, string $imageId)
    {
        $request->validate([
            'color' => 'nullable|string|max:50'
        ]);

        $image = ProductImage::findOrFail($imageId);
        $image->update(['color' => $request->color]);

        return response()->json([
            'success' => true,
            'image' => $image
        ]);
    }

    // ADMIN: Assign color to main image
    public function assignMainImageColor(Request $request, string $id)
    {
        $request->validate([
            'color' => 'nullable|string|max:50'
        ]);

        $product = Product::findOrFail($id);
        $product->update(['image_color' => $request->color]);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    // ADMIN: Upload additional images
    public function uploadImages(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Log request info for debugging
            \Log::info('Upload request received', [
                'product_id' => $id,
                'has_files' => $request->hasFile('images'),
                'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0
            ]);
            
            // Validate with better error messages
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'images' => 'required|array|min:1|max:5',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'images.required' => 'Vui lòng chọn ít nhất 1 ảnh',
                'images.*.image' => 'File phải là ảnh',
                'images.*.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif, webp',
                'images.*.max' => 'Kích thước ảnh không được vượt quá 2MB',
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $uploadedCount = 0;
            $uploadedImages = [];
            
            if ($request->hasFile('images')) {
                $maxPosition = $product->images()->max('position') ?? 0;
                
                foreach ($request->file('images') as $index => $image) {
                    try {
                        $path = $image->store('products', 'public');
                        
                        $productImage = ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $path,
                            'position' => $maxPosition + $index + 1,
                        ]);
                        
                        \Log::info('Image uploaded', ['path' => $path, 'id' => $productImage->id]);
                        
                        $uploadedImages[] = $productImage;
                        $uploadedCount++;
                    } catch (\Exception $e) {
                        \Log::error('Error uploading single image: ' . $e->getMessage());
                    }
                }
            }

            if ($uploadedCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có ảnh nào được tải lên. Vui lòng thử lại.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => "Đã tải lên {$uploadedCount} ảnh thành công!",
                'uploaded' => $uploadedCount,
                'images' => $uploadedImages
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload images error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    // ADMIN: Delete image
    public function deleteImage(string $productId, string $imageId)
    {
        $product = Product::findOrFail($productId);
        $image = ProductImage::where('product_id', $product->id)->findOrFail($imageId);
        
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ảnh đã được xóa thành công',
        ]);
    }

    // ADMIN: Store new variant
    public function storeVariant(Request $request, string $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if bulk create (multiple variants)
        if ($request->has('variants')) {
            $variants = $request->input('variants');
            $created = [];
            
            foreach ($variants as $variantData) {
                // Check if variant already exists
                $existing = ProductVariant::where('product_id', $product->id)
                    ->where('color', $variantData['color'])
                    ->where('size', $variantData['size'])
                    ->first();
                
                if ($existing) {
                    // Update existing variant
                    $existing->update([
                        'stock_quantity' => $variantData['stock_quantity'],
                        'price' => $variantData['price'],
                    ]);
                    $created[] = $existing;
                } else {
                    // Create new variant
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'price' => $variantData['price'],
                        'stock_quantity' => $variantData['stock_quantity'],
                    ]);
                    $created[] = $variant;
                }
            }
            
            $product->syncQuantity();

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu ' . count($created) . ' variants!',
                'variants' => $created
            ]);
        }
        
        // Single variant create (old method)
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $validated['sku'],
            'size' => $validated['size'],
            'color' => $validated['color'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
        ]);

        $product->syncQuantity();

        return response()->json([
            'success' => true,
            'message' => 'Variant đã được thêm thành công!',
            'variant' => $variant
        ]);
    }

    // ADMIN: Update variant
    public function updateVariant(Request $request, string $productId, string $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);
        
        // Quick stock update only
        if ($request->has('stock_quantity') && count($request->all()) <= 3) {
            $variant->update([
                'stock_quantity' => $request->input('stock_quantity')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng!',
                'variant' => $variant
            ]);
        }
        
        // Full update
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $variantId,
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $variant->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Variant đã được cập nhật thành công!',
            'variant' => $variant
        ]);
    }

    // ADMIN: Delete variant
    public function destroyVariant(string $productId, string $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);
        
        $variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant đã được xóa thành công!'
        ]);
    }
}

