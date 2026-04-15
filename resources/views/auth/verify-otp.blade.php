@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-shield-alt"></i> Xác Nhận Mã OTP</h3>
            </div>
            <div class="card-body p-5">
                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                    </div>
                @endif

                <p class="text-muted mb-4">
                    Mã xác nhận 6 số đã được gửi đến <strong>{{ $email }}</strong>.<br>
                    Mã có hiệu lực trong <strong>10 phút</strong>.
                </p>

                <form method="POST" action="{{ route('password.otp.verify') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mã xác nhận</label>
                        <input type="text" name="otp" inputmode="numeric" maxlength="6"
                               class="form-control form-control-lg text-center fw-bold fs-4 tracking-widest @error('otp') is-invalid @enderror"
                               placeholder="_ _ _ _ _ _" autocomplete="one-time-code" autofocus
                               style="letter-spacing: 8px;">
                        @error('otp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-check"></i> Xác Nhận
                    </button>
                </form>

                <div class="text-center">
                    <a href="{{ route('password.email') }}" class="text-muted small">
                        <i class="fas fa-redo me-1"></i>Gửi lại mã
                    </a>
                </div>
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
