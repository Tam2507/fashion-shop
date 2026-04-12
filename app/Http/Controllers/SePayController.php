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
        $merchantId = config('services.sepay.merchant_id');
        $secretKey  = config('services.sepay.secret_key');

        if (!$merchantId || !$secretKey) {
            abort(500, 'SePay chưa được cấu hình. Vui lòng thêm SEPAY_MERCHANT_ID và SEPAY_SECRET_KEY vào biến môi trường.');
        }

        return new SePayClient(
            $merchantId,
            $secretKey,
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

    // IPN - SePay gửi thông báo khi thanh toán thành công
    public function ipn(Request $request)
    {
        \Log::info('SePay IPN received', $request->all());

        // Xác thực API key từ header Authorization
        $apiKey = config('services.sepay.api_key');
        if ($apiKey) {
            $authHeader = $request->header('Authorization', '');
            // SePay gửi: "Apikey YOUR_API_KEY"
            if ($authHeader !== 'Apikey ' . $apiKey) {
                \Log::warning('SePay IPN: Invalid API key', ['header' => $authHeader]);
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
        }

        // Lấy dữ liệu từ SePay IPN payload
        $transferContent = $request->input('content', '');
        $amount          = (float) $request->input('transferAmount', 0);
        $transactionId   = $request->input('id', '');
        $accountNumber   = $request->input('accountNumber', '');

        \Log::info("SePay IPN - content: {$transferContent}, amount: {$amount}, txn: {$transactionId}");

        // Tìm order từ nội dung chuyển khoản (format: FS-{order_id})
        preg_match('/FS-(\d+)/i', $transferContent, $matches);
        $orderId = $matches[1] ?? null;

        if (!$orderId) {
            \Log::warning("SePay IPN: Cannot extract order ID from content: {$transferContent}");
            return response()->json(['success' => false, 'message' => 'Order not found in content'], 400);
        }

        $order = Order::find($orderId);
        if (!$order) {
            \Log::warning("SePay IPN: Order #{$orderId} not found");
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Kiểm tra số tiền khớp (cho phép sai lệch tối đa 1.000đ)
        if (abs($amount - $order->total_price) > 1000) {
            \Log::warning("SePay IPN: Amount mismatch - expected {$order->total_price}, got {$amount}");
            return response()->json(['success' => false, 'message' => 'Amount mismatch'], 400);
        }

        // Cập nhật trạng thái đơn hàng (chỉ khi đang ở trạng thái chờ thanh toán)
        if (in_array($order->status, ['received', 'pending'])) {
            $order->update([
                'status'         => 'confirmed',
                'payment_status' => 'paid',
            ]);
            \Log::info("SePay IPN: Order #{$orderId} confirmed. Transaction: {$transactionId}");
        } else {
            \Log::info("SePay IPN: Order #{$orderId} already in status '{$order->status}', skipped.");
        }

        return response()->json(['success' => true, 'message' => 'OK']);
    }
}
