<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'order_id' => 'required|exists:orders,id',
        ]);

        $product = Product::findOrFail($productId);

        // Kiểm tra đã review cho đơn hàng này chưa
        $exists = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('order_id', $request->input('order_id'))
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này cho đơn hàng này rồi.');
        }

        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'order_id'   => $request->input('order_id'),
            'rating'     => $request->input('rating'),
            'comment'    => $request->input('comment'),
            'approved'   => true,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công.');
    }
}
