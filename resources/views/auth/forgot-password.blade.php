@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-secondary text-white text-center py-4">
                <h4 class="mb-0"><i class="fas fa-redo"></i> Quên Mật Khẩu?</h4>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Nhập email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu.</p>
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">@csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-secondary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-paper-plane"></i> Gửi Link
                    </button>
                </form>
                <hr>
                <p class="text-center text-muted"><a href="{{ route('login') }}" class="text-primary fw-bold">Quay lại đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>
@endsection