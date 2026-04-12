@extends('layouts.app')
@section('title', 'Thanh Toán Thành Công')
@section('content')
<div class="container py-5 text-center">
    <i class="fas fa-check-circle text-success" style="font-size:4rem;"></i>
    <h2 class="mt-3">Thanh toán thành công!</h2>
    <p class="text-muted">Đơn hàng #{{ $order->id ?? '' }} đã được xác nhận.</p>
    <a href="{{ route('orders.index') }}" class="btn btn-primary">Xem đơn hàng</a>
</div>
@endsection
