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
        ]);

        $product = Product::findOrFail($productId);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'approved' => true,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công.');
    }
}
