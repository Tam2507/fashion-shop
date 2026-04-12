@extends('layouts.app')
@section('title', 'Thanh Toán Thất Bại')
@section('content')
<div class="container py-5 text-center">
    <i class="fas fa-times-circle text-danger" style="font-size:4rem;"></i>
    <h2 class="mt-3">Thanh toán thất bại!</h2>
    <p class="text-muted">Đã có lỗi xảy ra trong quá trình thanh toán.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Về trang chủ</a>
</div>
@endsection
