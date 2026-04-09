@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-key"></i> Quên Mật Khẩu</h3>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Nhập email đã đăng ký để tiếp tục đặt lại mật khẩu.</p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus placeholder="Nhập email của bạn">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-arrow-right"></i> Tiếp Tục
                    </button>
                </form>
                <hr>
                <p class="text-center text-muted">
                    <a href="{{ route('login') }}" class="text-primary fw-bold">
                        <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
