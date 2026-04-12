<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use SePay\SePayClient;
use SePay\Builders\CheckoutBuilder;

class SePayController extends Controller
{
    private function client(): SePayClient
    {
        return new SePayClient(
            config('services.sepay.merchant_id'),
            config('services.sepay.secret_key'),
            config('services.sepay.env', 'sandbox')
        );
    }

    // Tạo form thanh toán SePay
    public function checkout(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Chỉ cho phép chủ đơn hàng
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $appUrl = config('app.url');

        $checkoutData = CheckoutBuilder::make()
            ->currency('VND')
            ->orderInvoiceNumber('FS-' . $order->id)
            ->orderAmount((int) $order->total_price)
            ->operation('PURCHASE')
            ->orderDescription('Thanh toán đơn hàng #' . $order->id . ' - Fashion Shop')
            ->successUrl($appUrl . '/payment/success?order_id=' . $order->id)
            ->errorUrl($appUrl . '/payment/error?order_id=' . $order->id)
            ->cancelUrl($appUrl . '/payment/cancel?order_id=' . $order->id)
            ->build();

        $formHtml = $this->client()->checkout()->generateFormHtml($checkoutData);

        return view('payment.sepay', compact('order', 'formHtml'));
    }

    public function success(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->update(['status' => 'confirmed']);
        }
        return view('payment.success', compact('order'));
    }

    public function error(Request $request)
    {
        $order = Order::find($request->order_id);
        return view('payment.error', compact('order'));
    }

    public function cancel(Request $request)
    {
        $order = Order::find($request->order_id);
        return view('payment.cancel', compact('order'));
    }
}
