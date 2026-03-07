<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
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
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $carts = Cart::where('user_id', auth()->id())->with(['product', 'variant'])->get();
        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Giỏ hàng trống');
        }

        $total = $carts->sum(function($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'shipping_address' => $validated['shipping_address'],
            'phone' => $validated['phone'],
            'payment_method_id' => $validated['payment_method_id'],
            'status' => 'received',
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
                // Deduct from variant stock
                $variant = $cart->variant;
                if ($variant) {
                    $variant->decrement('stock_quantity', $cart->quantity);
                }
            } else {
                // Deduct from product stock
                $cart->product->decrement('quantity', $cart->quantity);
            }
        }

        Cart::where('user_id', auth()->id())->delete();

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
                $total,
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

    // ADMIN: Xem tất cả đơn hàng
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'orderItems'])->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(15);
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
            'status' => 'required|in:received,processing,confirmed,shipped,delivered,cancelled,refunded'
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
        if (!in_array($order->status, ['cancelled', 'refunded'])) {
            return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy hoặc đã hoàn tiền');
        }
        
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng');
    }
}

