@extends('layouts.app')

@section('content')
<h1>Chi tiết đơn hàng #{{ $order->id }}</h1>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white"><h5 class="mb-0">Sản phẩm</h5></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Sản phẩm</th><th>Giá</th><th>SL</th><th>Thành tiền</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $it)
                        <tr>
                            <td>{{ $it->product->name ?? 'N/A' }}</td>
                            <td>{{ number_format($it->price, 0, ',', '.') }}</td>
                            <td>{{ $it->quantity }}</td>
                            <td class="fw-bold">{{ number_format($it->price * $it->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary text-white"><h5 class="mb-0">Thông tin</h5></div>
            <div class="card-body">
                <p><strong>Tổng:</strong> <span class="text-primary fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} VND</span></p>
                <p><strong>Trạng thái:</strong> <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></p>
                <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                @if($order->status === 'received')
                    <a href="{{ route('payment.sepay', $order->id) }}" class="btn btn-success w-100 mt-2">
                        <i class="fas fa-credit-card me-2"></i>Thanh toán ngay
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
<a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
@endsection