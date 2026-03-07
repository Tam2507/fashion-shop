@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Đơn hàng của tôi</h1>
    @forelse($orders as $order)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $order->id }}</h5>
                <span class="badge bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
            </div>
            <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div class="card-body">
            @foreach($order->items as $item)
            <div class="row mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="col-md-2 col-3">
                    @php
                        $displayImage = $item->product->image ?? $item->product->images->first()->path ?? null;
                    @endphp
                    @if($displayImage)
                        <img src="/storage/{{ $displayImage }}" 
                             alt="{{ $item->product->name }}" 
                             class="img-fluid rounded"
                             style="width: 100%; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                            <i class="fas fa-box text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-9">
                    <h6 class="mb-1">{{ $item->product->name ?? 'N/A' }}</h6>
                    @if($item->variant)
                        <small class="text-muted">
                            @if($item->variant->size)
                                Size: {{ $item->variant->size }}
                            @endif
                            @if($item->variant->color)
                                | Màu: {{ $item->variant->color }}
                            @endif
                        </small>
                    @endif
                    <div class="mt-1">
                        <small>Số lượng: {{ $item->quantity }}</small>
                    </div>
                </div>
                <div class="col-md-4 col-12 text-md-end mt-2 mt-md-0">
                    <div class="text-muted small">{{ number_format($item->price, 0, ',', '.') }} VND</div>
                    <div class="fw-bold text-primary">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</div>
                </div>
            </div>
            @endforeach
            
            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-8">
                    <p class="mb-1"><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                    <p class="mb-0"><strong>SĐT:</strong> {{ $order->phone }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="mb-0">Tổng cộng: <span class="text-primary">{{ number_format($order->total_price, 0, ',', '.') }} VND</span></h5>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        <p class="mb-0">Chưa có đơn hàng nào.</p>
    </div>
    @endforelse
    <div class="d-flex justify-content-center">{{ $orders->links() }}</div>
</div>
@endsection