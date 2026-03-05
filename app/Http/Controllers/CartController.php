<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function count()
    {
        $count = Cart::where('user_id', auth()->id())->sum('quantity');
        return response()->json(['count' => $count]);
    }

    public function index()
    {
        $carts = Cart::where('user_id', auth()->id())
            ->with(['product.category', 'product.images', 'variant'])
            ->get();
        
        $total = $carts->sum(function($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        });
        
        return view('cart.index', compact('carts', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::with(['variants', 'images', 'category'])->findOrFail($productId);
        $quantity = (int) $request->input('quantity', 1);
        $variantId = $request->input('variant_id');

        // Quick validation
        if ($quantity < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng không hợp lệ.'
            ]);
        }

        // Check if product actually has variants in database
        $hasVariants = $product->variants()->count() > 0;
        
        // Only require variant_id if product has variants
        if ($hasVariants && !$variantId) {
            // Check if product is accessory
            $isAccessory = $product->category && 
                           (stripos($product->category->name, 'phụ kiện') !== false || 
                            stripos($product->category->name, 'accessory') !== false ||
                            stripos($product->category->name, 'accessories') !== false);
            
            $errorMessage = 'Vui lòng chọn ';
            $required = ['màu sắc'];
            
            if (!$isAccessory) {
                $required[] = 'kích thước';
            }
            
            $errorMessage .= implode(' và ', $required) . ' trước khi thêm vào giỏ hàng.';
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }

        // If variant specified, check stock
        if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if (!$variant || $variant->stock_quantity < $quantity) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá tồn kho variant.'
                    ]);
                }
                return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho variant.');
            }
        } else {
            if ($product->quantity < $quantity) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá tồn kho sản phẩm.'
                    ]);
                }
                return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho sản phẩm.');
            }
        }

        // Check if item already in cart
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingCart) {
            $existingCart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        if ($request->wantsJson()) {
            $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request, $cartId)
    {
        $cart = Cart::findOrFail($cartId);
        $quantity = $request->input('quantity');
        
        $cart->update(['quantity' => $quantity]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công',
                'quantity' => $quantity
            ]);
        }
        
        return redirect()->back()->with('success', 'Cập nhật giỏ hàng');
    }

    public function remove($cartId)
    {
        Cart::findOrFail($cartId)->delete();
        return redirect()->back()->with('success', 'Đã xóa khỏi giỏ hàng');
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();
        return redirect()->back()->with('success', 'Đã xóa tất cả');
    }
}
