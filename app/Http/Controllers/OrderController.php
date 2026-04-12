<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Hiển thị lịch sử đơn hàng
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product.images', 'items.variant'])
            ->latest()
            ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Hiển thị chi tiết đơn hàng
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Admin can view any order; regular users only their own orders
        if (! $user->is_admin && $order->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        return view('orders.show', compact('order'));
    }

    // Tạo đơn hàng từ giỏ hàng
    public function create(Request $request)
    {
        $selectedIds = $request->input('selected_items');
        
        if ($selectedIds) {
            $ids = explode(',', $selectedIds);
            $carts = Cart::whereIn('id', $ids)->where('user_id', auth()->id())->with('product')->get();
        } else {
            $carts = Cart::where('user_id', auth()->id())->with('product')->get();
        }
        
        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng trống');
        }
        return view('orders.create', compact('carts'));
    }

    // Lưu đơn hàng
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
            'payment_method_id' => 'required',
        ]);

        // Nếu chọn SePay thì không cần check DB
        if ($validated['payment_method_id'] !== 'sepay') {
            $request->validate([
                'payment_method_id' => 'exists:payment_methods,id',
            ]);
        }

        $carts = Cart::where('user_id', auth()->id())->with(['product', 'variant'])->get();
        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng trống');
        }

        $total = $carts->sum(function($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        });

        // Áp dụng mã giảm giá nếu có
        $couponCode = strtoupper(trim($request->input('coupon_code', '')));
        $discount = 0;
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->canBeUsedBy(auth()->id())) {
                $discount = $coupon->calculateDiscount($total);
                $coupon->increment('used_count');
            }
        }
        $finalTotal = max(0, $total - $discount);

        $order = Order::create([
            'user_id'          => auth()->id(),
            'total_price'      => $finalTotal,
            'shipping_address' => $validated['shipping_address'],
            'phone'            => $validated['phone'],
            'payment_method_id'=> $validated['payment_method_id'] !== 'sepay' ? $validated['payment_method_id'] : null,
            'status'           => 'received',
            'coupon_code'      => $couponCode ?: null,
        ]);

        foreach ($carts as $cart) {
            $price = $cart->variant ? $cart->variant->final_price : $cart->product->price;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'variant_id' => $cart->variant_id,
                'quantity' => $cart->quantity,
                'price' => $price,
            ]);
            
            // Deduct stock from variant or product
            if ($cart->variant_id) {
                $variant = $cart->variant;
                if ($variant) {
                    $variant->decrement('stock_quantity', $cart->quantity);
                    // Đồng bộ quantity sản phẩm
                    $cart->product->syncQuantity();
                }
            } else {
                $cart->product->decrement('quantity', $cart->quantity);
            }
        }

        Cart::where('user_id', auth()->id())->delete();

        // Check if payment method is SePay
        if ($validated['payment_method_id'] === 'sepay') {
            return redirect()->route('payment.sepay', $order->id);
        }

        // Check if payment method is SePay
        if ($validated['payment_method_id'] === 'sepay') {
            return redirect()->route('payment.sepay', $order->id);
        }

        // Check if payment method is ATM (MoMo)
        $paymentMethod = \App\Models\PaymentMethod::find($validated['payment_method_id']);
        if ($paymentMethod && $paymentMethod->code === 'atm') {
            // Check if MoMo is configured
            if (empty(env('MOMO_PARTNER_CODE')) || empty(env('MOMO_ACCESS_KEY')) || empty(env('MOMO_SECRET_KEY'))) {
                // MoMo not configured - simulate payment for demo
                return redirect()->route('momo.demo', ['order_id' => $order->id]);
            }
            
            // Redirect to MoMo payment gateway
            $momoService = new \App\Services\MoMoService();
            $result = $momoService->createPayment(
                $order->id,
                $finalTotal,
                "Thanh toán đơn hàng #{$order->id}"
            );
            
            if (isset($result['payUrl']) && $result['resultCode'] == 0) {
                return redirect($result['payUrl']);
            } else {
                $message = $momoService->getStatusMessage($result['resultCode'] ?? 99);
                return redirect()->route('orders.show', $order)->with('error', 'Không thể tạo thanh toán MoMo: ' . $message);
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Đặt hàng thành công');
    }

    // Kiểm tra và áp dụng mã giảm giá (AJAX)
    public function applyCoupon(Request $request)
    {
        $code  = strtoupper(trim($request->input('code', '')));
        $total = (float) $request->input('total', 0);

        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }
        if (!$coupon->canBeUsedBy(auth()->id())) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt dùng.']);
        }

        $discount = $coupon->calculateDiscount($total);
        if ($discount <= 0) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng chưa đạt điều kiện áp dụng mã này (tối thiểu ' . number_format($coupon->minimum_amount, 0, ',', '.') . '₫).']);
        }

        return response()->json([
            'success'  => true,
            'discount' => $discount,
            'message'  => 'Áp dụng thành công! Giảm ' . number_format($discount, 0, ',', '.') . '₫',
        ]);
    }

    // Mua ngay - bypass giỏ hàng, đi thẳng tới checkout
    public function buyNow(Request $request, $productId)
    {
        $product = Product::with(['variants', 'category'])->findOrFail($productId);
        $quantity = (int) $request->input('quantity', 1);
        $variantId = $request->input('variant_id') ?: null;

        // Kiểm tra tồn kho
        if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if (!$variant || $variant->stock_quantity < $quantity) {
                return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho. Chỉ còn ' . ($variant->stock_quantity ?? 0) . ' sản phẩm.');
            }
        } else {
            if ($product->quantity < $quantity) {
                return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho sản phẩm.');
            }
        }

        // Xóa cart tạm cũ (nếu có) rồi thêm item mới
        Cart::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->delete();

        $cart = Cart::create([
            'user_id'    => auth()->id(),
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity'   => $quantity,
        ]);

        // Redirect thẳng tới checkout với cart item này
        return redirect()->route('orders.create', ['selected_items' => $cart->id]);
    }
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'orderItems'])->latest();
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by order ID, customer name, phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    // ADMIN: Xem chi tiết đơn hàng
    public function adminShow(string $id)
    {
        $order = Order::with(['user', 'orderItems.product', 'orderItems.variant'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // ADMIN: Cập nhật trạng thái đơn hàng
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:received,processing,confirmed,delivered,cancelled'
        ]);
        
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);
        
        return redirect()->back()->with('success', "Đã cập nhật trạng thái đơn hàng từ '{$oldStatus}' sang '{$validated['status']}'");
    }

    // ADMIN: In phiếu giao hàng
    public function printShippingLabel(Request $request, string $id)
    {
        $order = Order::with(['user', 'orderItems.product', 'orderItems.variant'])->findOrFail($id);
        $shippingProvider = $request->input('shipping_provider', 'ghn'); // Default: Giao Hàng Nhanh
        
        return view('admin.orders.shipping-label', compact('order', 'shippingProvider'));
    }

    // ADMIN: Xóa đơn hàng
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy');
        }
        
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng');
    }
}

