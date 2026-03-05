@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #a60f93 0%, #d31e82 100%);">
                    <h3 class="mb-0">
                        <i class="fas fa-wallet"></i> MoMo Payment Demo
                    </h3>
                    <small>Trang thanh toán giả lập</small>
                </div>
                <div class="card-body p-5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Chế độ Demo:</strong> Đây là trang thanh toán giả lập vì bạn chưa cấu hình MoMo credentials.
                    </div>

                    <div class="mb-4">
                        <h5 class="text-center mb-3">Thông tin đơn hàng</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">Mã đơn hàng:</td>
                                <td class="text-end"><strong>#{{ $order->id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Số tiền:</td>
                                <td class="text-end text-danger"><strong>{{ number_format($order->total_price, 0, ',', '.') }} ₫</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phương thức:</td>
                                <td class="text-end"><span class="badge bg-primary">MoMo E-Wallet</span></td>
                            </tr>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="text-center mb-4">
                        <img src="https://developers.momo.vn/v3/img/logo.svg" alt="MoMo" style="height: 60px;">
                        <p class="text-muted mt-3">Quét mã QR để thanh toán</p>
                        
                        @if(env('MOMO_STATIC_QR'))
                            <!-- Static QR Code from personal MoMo account -->
                            <div class="my-4">
                                <img src="{{ env('MOMO_STATIC_QR') }}" alt="MoMo QR" class="img-fluid" style="max-width: 300px; border: 2px solid #a60f93; border-radius: 10px; padding: 10px;">
                                <p class="text-muted mt-2">
                                    <small>Quét mã QR bằng app MoMo<br>
                                    Số tiền: <strong class="text-danger">{{ number_format($order->total_price, 0, ',', '.') }} ₫</strong><br>
                                    Nội dung: <strong>DH{{ $order->id }}</strong></small>
                                </p>
                            </div>
                            
                            <div class="alert alert-warning">
                                <small>
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Lưu ý:</strong> Sau khi chuyển khoản, vui lòng nhấn nút "Đã thanh toán" bên dưới và đợi xác nhận.
                                </small>
                            </div>
                        @else
                            <p class="text-muted">Chưa cấu hình QR code</p>
                        @endif
                    </div>

                    <div class="d-grid gap-3">
                        <form method="POST" action="{{ route('momo.demo.process') }}">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="action" value="success">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-check-circle"></i> Đã thanh toán
                            </button>
                        </form>

                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary btn-lg w-100">
                            <i class="fas fa-arrow-left"></i> Quay lại đơn hàng
                        </a>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <small>
                            <strong>Cách lấy QR MoMo của bạn:</strong><br>
                            1. Mở app MoMo → Nhấn vào ảnh đại diện<br>
                            2. Chọn "Mã QR của tôi"<br>
                            3. Chụp ảnh QR và upload lên server<br>
                            4. Thêm vào .env: <code>MOMO_STATIC_QR=url_to_qr_image</code>
                        </small>
                    </div>

                    <div class="alert alert-warning mt-2 mb-0">
                        <small>
                            <strong>Để tích hợp API MoMo tự động:</strong><br>
                            Đăng ký tài khoản doanh nghiệp tại <a href="https://business.momo.vn/" target="_blank">business.momo.vn</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
