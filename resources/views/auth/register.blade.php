@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-secondary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-user-plus"></i> Đăng Ký Tài Khoản</h3>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('register') }}">@csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ tên</label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="reg-password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   style="padding-right:2.8rem;" required>
                            <i class="fas fa-eye" id="reg-eye1" onclick="togglePwd('reg-password','reg-eye1')"
                               style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#6c757d;"></i>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Mật khẩu phải có ít nhất 8 ký tự</small>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="reg-password2"
                                   class="form-control form-control-lg"
                                   style="padding-right:2.8rem;" required>
                            <i class="fas fa-eye" id="reg-eye2" onclick="togglePwd('reg-password2','reg-eye2')"
                               style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#6c757d;"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-user-check"></i> Đăng Ký
                    </button>
                </form>
                <hr>
                <p class="text-center text-muted">Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary fw-bold">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePwd(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') { f.type = 'text'; i.classList.replace('fa-eye','fa-eye-slash'); }
    else { f.type = 'password'; i.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
@endsection