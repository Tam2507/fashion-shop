@extends('layouts.app')
@section('title', 'Thanh Toán SePay')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thanh toán đơn hàng #{{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Tổng tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></p>
                    {!! $formHtml !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
