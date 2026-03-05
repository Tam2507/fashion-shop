<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\MoMoService;
use Illuminate\Http\Request;

class MoMoController extends Controller
{
    protected $momoService;

    public function __construct(MoMoService $momoService)
    {
        $this->momoService = $momoService;
    }

    /**
     * Demo payment page (for testing without real MoMo credentials)
     */
    public function demo(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        return view('momo.demo', compact('order'));
    }

    /**
     * Process demo payment
     */
    public function processDemoPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $action = $request->input('action'); // 'success' or 'cancel'
        
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        if ($action === 'success') {
            // Simulate successful payment
            PaymentTransaction::create([
                'order_id' => $order->id,
                'transaction_id' => 'DEMO_' . time(),
                'payment_method' => 'momo_demo',
                'amount' => $order->total_price,
                'status' => 'completed',
                'response_code' => 0,
                'transaction_date' => now(),
                'response_data' => json_encode(['demo' => true]),
            ]);

            $order->update(['status' => 'confirmed']);
            return redirect()->route('orders.show', $order)->with('success', 'Thanh toán demo thành công! Đơn hàng đã được xác nhận.');
        } else {
            return redirect()->route('orders.show', $order)->with('error', 'Bạn đã hủy thanh toán.');
        }
    }

    /**
     * Handle callback from MoMo (user redirect)
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        
        // Verify signature
        if (!$this->momoService->verifySignature($data)) {
            return redirect()->route('orders.index')->with('error', 'Chữ ký không hợp lệ');
        }

        $orderId = $data['orderId'];
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Save transaction
        $this->saveTransaction($data, $order);

        // Update order status based on payment result
        if ($data['resultCode'] == 0) {
            $order->update(['status' => 'confirmed']);
            return redirect()->route('orders.show', $order)->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.');
        } else {
            $message = $this->momoService->getStatusMessage($data['resultCode']);
            return redirect()->route('orders.show', $order)->with('error', 'Thanh toán thất bại: ' . $message);
        }
    }

    /**
     * Handle IPN (Instant Payment Notification) from MoMo
     */
    public function notify(Request $request)
    {
        $data = $request->all();
        
        // Verify signature
        if (!$this->momoService->verifySignature($data)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $orderId = $data['orderId'];
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Save transaction
        $this->saveTransaction($data, $order);

        // Update order status
        if ($data['resultCode'] == 0) {
            $order->update(['status' => 'confirmed']);
        }

        // Return success to MoMo
        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * Save payment transaction
     */
    private function saveTransaction($data, $order)
    {
        PaymentTransaction::updateOrCreate(
            [
                'order_id' => $order->id,
                'transaction_id' => $data['transId'] ?? null,
            ],
            [
                'payment_method' => 'momo',
                'amount' => $data['amount'],
                'status' => $data['resultCode'] == 0 ? 'completed' : 'failed',
                'response_code' => $data['resultCode'],
                'bank_code' => $data['payType'] ?? null,
                'transaction_date' => now(),
                'response_data' => json_encode($data),
            ]
        );
    }
}
