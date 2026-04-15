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
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   required placeholder="Nhập mật khẩu mới" minlength="8">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'eye1')">
                                <i class="fas fa-eye" id="eye1"></i>
                            </button>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Mật khẩu phải có ít nhất 8 ký tự</small>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-lg"
                                   required placeholder="Nhập lại mật khẩu mới" minlength="8">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'eye2')">
                                <i class="fas fa-eye" id="eye2"></i>
                            </button>
                        </div>
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

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
