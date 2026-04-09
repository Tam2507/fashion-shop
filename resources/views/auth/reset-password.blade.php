@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-lock"></i> Đặt Lại Mật Khẩu</h3>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Đặt mật khẩu mới cho tài khoản <strong>{{ $email }}</strong></p>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật Khẩu Mới</label>
                        <input type="password" name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               required placeholder="Nhập mật khẩu mới (ít nhất 8 ký tự)">
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                        <input type="password" name="password_confirmation"
                               class="form-control form-control-lg"
                               required placeholder="Nhập lại mật khẩu mới">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-save"></i> Lưu Mật Khẩu Mới
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
