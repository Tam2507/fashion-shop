<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewReply;
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

    public function storeReply(Request $request, $reviewId)
    {
        $request->validate([
            'comment'    => 'required|string|max:1000',
            'guest_name' => 'nullable|string|max:255',
        ]);

        $review = Review::findOrFail($reviewId);

        ReviewReply::create([
            'review_id'  => $review->id,
            'user_id'    => auth()->id(),
            'guest_name' => auth()->check() ? null : ($request->input('guest_name') ?: 'Khách'),
            'comment'    => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Phản hồi của bạn đã được gửi.');
    }
}
