@extends('layouts.app')

@section('content')
<h1>Đơn hàng của tôi</h1>
@forelse($orders as $order)
<div class="card mb-2">
    <div class="card-body">
        <h5>Order #{{ $order->id }} - {{ ucfirst($order->status) }}</h5>
        <p>Tổng: {{ number_format($order->total_price,0,',','.') }} VND</p>
        <a href="{{ route('orders.show', $order->id) }}">Chi tiết</a>
    </div>
</div>
@empty
<p>Chưa có đơn hàng.</p>
@endforelse
<div class="d-flex justify-content-center">{{ $orders->links() }}</div>
@endsection