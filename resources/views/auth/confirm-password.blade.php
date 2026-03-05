@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-warning text-white text-center py-4">
                <h4 class="mb-0"><i class="fas fa-lock"></i> Xác Nhận Mật Khẩu</h4>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Vui lòng xác nhận mật khẩu để tiếp tục.</p>
                <form method="POST" action="{{ route('password.confirm') }}">@csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold">
                        <i class="fas fa-check"></i> Xác Nhận
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection