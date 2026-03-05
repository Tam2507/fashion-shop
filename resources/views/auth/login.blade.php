@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-sign-in-alt"></i> Đăng Nhập</h3>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('login') }}">@csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ghi nhớ tôi</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-lock-open"></i> Đăng Nhập
                    </button>
                </form>
                <hr>
                <p class="text-center text-muted">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-primary fw-bold">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
</div>
@endsection