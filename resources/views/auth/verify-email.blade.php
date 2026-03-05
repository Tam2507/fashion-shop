@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-info text-white text-center py-4">
                <h4 class="mb-0"><i class="fas fa-envelope"></i> Xác Minh Email</h4>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Cảm ơn đã đăng ký! Vui lòng kiểm tra email của bạn để xác minh tài khoản.</p>
                <form method="POST" action="{{ route('verification.send') }}">@csrf
                    <button type="submit" class="btn btn-info btn-lg w-100">
                        <i class="fas fa-paper-plane"></i> Gửi lại email xác minh
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection