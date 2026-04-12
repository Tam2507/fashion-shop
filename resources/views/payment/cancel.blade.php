@extends('layouts.app')
@section('title', 'Đã Hủy Thanh Toán')
@section('content')
<div class="container py-5 text-center">
    <i class="fas fa-ban text-warning" style="font-size:4rem;"></i>
    <h2 class="mt-3">Đã hủy thanh toán</h2>
    <p class="text-muted">Bạn đã hủy quá trình thanh toán.</p>
    <a href="{{ route('cart.index') }}" class="btn btn-primary">Quay lại giỏ hàng</a>
</div>
@endsection
