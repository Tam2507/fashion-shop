@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i>Lịch Sử Mua Hàng</h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-shopping-bag me-1"></i>Tiếp tục mua sắm
        </a>
    </div>

    {{-- Thống kê nhanh --}}
    @php
        $totalOrders = $orders->total();
        $totalSpent = $orders->sum('total_price');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-primary text-white text-center p-3">
                <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
                <small>Tổng đơn hàng</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-success text-white text-center p-3">
                <div class="fs-3 fw-bold">{{ $orders->where('status', 'delivered')->count() }}</div>
                <small>Đã nhận hàng</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-warning text-dark text-center p-3">
                <div class="fs-3 fw-bold">{{ $orders->whereIn('status', ['pending','processing','shipping'])->count() }}</div>
                <small>Đang xử lý</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-dark text-white text-center p-3">
                <div class="fs-5 fw-bold">{{ number_format($totalSpent, 0, ',', '.') }}₫</div>
                <small>Tổng chi tiêu</small>
            </div>
        </div>
    </div>

    {{-- Filter trạng thái --}}
    @php $currentStatus = request('status', ''); @endphp
    <div class="d-flex gap-2 flex-wrap mb-4">
        @foreach(['' => 'Tất cả', 'pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'delivered' => 'Đã nhận', 'cancelled' => 'Đã hủy'] as $val => $label)
        <a href="{{ route('orders.index', $val ? ['status' => $val] : []) }}"
           class="btn btn-sm {{ $currentStatus === $val ? 'btn-dark' : 'btn-outline-secondary' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Danh sách đơn hàng --}}
    @forelse($orders as $order)
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div>
                <span class="fw-bold text-dark">Đơn #{{ $order->id }}</span>
                <span class="text-muted small ms-3">
                    <i class="fas fa-clock me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-{{ $order->status_color }} px-3 py-2">{{ $order->status_label }}</span>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Chi tiết
                </a>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="row align-items-center">
                {{-- Ảnh sản phẩm --}}
                <div class="col-md-7">
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($order->items->take(4) as $item)
                        @php $img = $item->product->image ?? ($item->product->images->first()->path ?? null); @endphp
                        <div class="position-relative">
                            @if($img)
                                <img src="{{ \App\Services\ImageUploadService::url($img) }}"
                                     alt="{{ $item->product->name ?? '' }}"
                                     style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;border-radius:8px;">
                                    <i class="fas fa-box text-muted"></i>
                                </div>
                            @endif
                        </div>
                        @endforeach
                        @if($order->items->count() > 4)
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                 style="width:60px;height:60px;border-radius:8px;">
                                <span class="text-muted small">+{{ $order->items->count() - 4 }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2 text-muted small">
                        {{ $order->items->count() }} sản phẩm
                        @if($order->items->first())
                            &nbsp;·&nbsp; {{ Str::limit($order->items->first()->product->name ?? '', 40) }}
                            @if($order->items->count() > 1) và {{ $order->items->count() - 1 }} sản phẩm khác @endif
                        @endif
                    </div>
                </div>
                {{-- Tổng tiền và địa chỉ --}}
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="fw-bold text-primary fs-5">{{ number_format($order->total_price, 0, ',', '.') }} ₫</div>
                    <div class="text-muted small mt-1">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($order->shipping_address, 50) }}
                    </div>
                    @if($order->payment_method)
                    <div class="text-muted small">
                        <i class="fas fa-credit-card me-1"></i>{{ $order->payment_method->name ?? 'N/A' }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có đơn hàng nào</h5>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Mua sắm ngay</a>
    </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">{{ $orders->appends(request()->query())->links() }}</div>
</div>
@endsection