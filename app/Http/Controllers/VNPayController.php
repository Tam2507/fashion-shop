<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\VNPayService;
use Illuminate\Http\Request;

class VNPayController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Handle callback from VNPay
     */
    public function callback(Request $request)
    {
        $inputData = $request->all();
        
        // Verify signature
        if (!$this->vnpayService->verifyCallback($inputData)) {
            return redirect()->route('orders.index')->with('error', 'Chữ ký không hợp lệ');
        }

        $vnp_TxnRef = $inputData['vnp_TxnRef'];
        $vnp_ResponseCode = $inputData['vnp_ResponseCode'];
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // Convert back to VND
        $vnp_TransactionNo = $inputData['vnp_TransactionNo'] ?? null;
        $vnp_BankCode = $inputData['vnp_BankCode'] ?? null;
        $vnp_PayDate = $inputData['vnp_PayDate'] ?? null;

        // Extract order ID from transaction reference
        $orderId = explode('_', $vnp_TxnRef)[0];
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Create payment transaction record
        PaymentTransaction::create([
            'order_id' => $order->id,
            'transaction_id' => $vnp_TransactionNo,
            'payment_method' => 'vnpay',
            'amount' => $vnp_Amount,
            'status' => $vnp_ResponseCode === '00' ? 'completed' : 'failed',
            'response_code' => $vnp_ResponseCode,
            'bank_code' => $vnp_BankCode,
            'transaction_date' => $vnp_PayDate ? \Carbon\Carbon::createFromFormat('YmdHis', $vnp_PayDate) : now(),
            'response_data' => json_encode($inputData),
        ]);

        // Update order status based on payment result
        if ($vnp_ResponseCode === '00') {
            $order->update(['status' => 'confirmed']);
            return redirect()->route('orders.show', $order)->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.');
        } else {
            $message = $this->vnpayService->getTransactionStatus($vnp_ResponseCode);
            return redirect()->route('orders.show', $order)->with('error', 'Thanh toán thất bại: ' . $message);
        }
    }
}
