<?php

namespace App\Services;

class MoMoService
{
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $endpoint;
    private $returnUrl;
    private $notifyUrl;

    public function __construct()
    {
        $this->partnerCode = env('MOMO_PARTNER_CODE', '');
        $this->accessKey = env('MOMO_ACCESS_KEY', '');
        $this->secretKey = env('MOMO_SECRET_KEY', '');
        $this->endpoint = env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $this->returnUrl = env('MOMO_RETURN_URL', url('/momo/callback'));
        $this->notifyUrl = env('MOMO_NOTIFY_URL', url('/momo/notify'));
    }

    /**
     * Create payment request to MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo)
    {
        $requestId = $orderId . '_' . time();
        $extraData = ""; // Empty string as default

        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $this->notifyUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $this->partnerCode .
                   "&redirectUrl=" . $this->returnUrl .
                   "&requestId=" . $requestId .
                   "&requestType=captureWallet";

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'requestId' => $requestId,
            'amount' => (int)$amount, // Must be integer
            'orderId' => (string)$orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'signature' => $signature
        ];

        $result = $this->execPostRequest($this->endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        return $jsonResult;
    }

    /**
     * Verify signature from MoMo callback
     */
    public function verifySignature($data)
    {
        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $data['amount'] .
                   "&extraData=" . $data['extraData'] .
                   "&message=" . $data['message'] .
                   "&orderId=" . $data['orderId'] .
                   "&orderInfo=" . $data['orderInfo'] .
                   "&orderType=" . $data['orderType'] .
                   "&partnerCode=" . $data['partnerCode'] .
                   "&payType=" . $data['payType'] .
                   "&requestId=" . $data['requestId'] .
                   "&responseTime=" . $data['responseTime'] .
                   "&resultCode=" . $data['resultCode'] .
                   "&transId=" . $data['transId'];

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        return $signature === $data['signature'];
    }

    /**
     * Execute POST request
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }

    /**
     * Get transaction status message
     */
    public function getStatusMessage($resultCode)
    {
        $messages = [
            0 => 'Giao dịch thành công',
            9000 => 'Giao dịch được xác nhận thành công',
            1000 => 'Giao dịch đã được khởi tạo, chờ người dùng xác nhận thanh toán',
            1001 => 'Giao dịch thất bại do tài khoản người dùng không đủ tiền',
            1002 => 'Giao dịch bị từ chối bởi nhà phát hành tài khoản người dùng',
            1003 => 'Giao dịch bị hủy',
            1004 => 'Giao dịch thất bại do số tiền thanh toán vượt quá hạn mức thanh toán của người dùng',
            1005 => 'Giao dịch thất bại do url hoặc QR code đã hết hạn',
            1006 => 'Giao dịch thất bại do người dùng đã từ chối xác nhận thanh toán',
            1007 => 'Giao dịch bị từ chối vì tài khoản người dùng đang ở trạng thái tạm khóa',
            1026 => 'Giao dịch bị hạn chế theo thể lệ chương trình khuyến mãi',
            1080 => 'Giao dịch hoàn tiền bị từ chối. Người dùng không nhận được tiền',
            1081 => 'Giao dịch hoàn tiền đang được xử lý',
            2001 => 'Giao dịch thất bại do sai thông tin',
            3001 => 'Liên kết thanh toán không tồn tại',
            3002 => 'Liên kết thanh toán đã hết hạn',
            3003 => 'Liên kết thanh toán không đúng',
            3004 => 'Số tiền thanh toán không hợp lệ',
            4001 => 'Giao dịch bị từ chối do vi phạm chính sách',
            4010 => 'Đơn hàng không tồn tại',
            4011 => 'Yêu cầu bị trùng',
            4100 => 'Giao dịch thất bại do người dùng không xác thực thành công',
            10 => 'Hệ thống đang được bảo trì',
            11 => 'Truy cập bị từ chối',
            12 => 'Phiên bản API không được hỗ trợ',
            13 => 'Xác thực merchant thất bại',
            20 => 'Yêu cầu sai định dạng',
            21 => 'Số tiền không hợp lệ',
            40 => 'RequestId bị trùng',
            41 => 'OrderId bị trùng',
            42 => 'OrderId không hợp lệ hoặc không được tìm thấy',
            43 => 'Yêu cầu bị từ chối vì xung đột trong quá trình xử lý giao dịch',
            1017 => 'Giao dịch bị hủy do người dùng không hoàn tất thanh toán trong thời gian quy định',
        ];

        return $messages[$resultCode] ?? 'Lỗi không xác định';
    }
}
