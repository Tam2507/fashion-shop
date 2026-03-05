<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product.category', 'product.images', 'product.reviews'])
            ->latest()
            ->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích'
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã thêm vào yêu thích');
    }

    public function destroy($productId)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();
            
        return redirect()->back()->with('success', 'Đã xóa khỏi yêu thích');
    }
    
    public function clear()
    {
        Wishlist::where('user_id', auth()->id())->delete();
        return redirect()->back()->with('success', 'Đã xóa tất cả sản phẩm khỏi danh sách yêu thích');
    }
}
