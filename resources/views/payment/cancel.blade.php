@extends('layouts.app')
@section('title', 'Đã Hủy Thanh Toán')
@section('content')
<div class="container py-5 text-center" style="max-width:500px;">
    <i class="fas fa-times-circle text-danger" style="font-size:4rem;"></i>
    <h2 class="mt-3 fw-bold">Đã hủy thanh toán</h2>
    <p class="text-muted">Bạn đã hủy quá trình thanh toán. Đơn hàng đã được hủy và tồn kho đã được hoàn lại.</p>
    @if($order)
    <p class="text-muted small">Đơn hàng #{{ $order->id }} — <span class="badge bg-danger">Đã hủy</span></p>
    @endif
    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Tiếp tục mua sắm</a>
</div>
@endsection
