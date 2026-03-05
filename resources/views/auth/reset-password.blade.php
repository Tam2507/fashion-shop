@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-info text-white text-center py-4">
                <h4 class="mb-0"><i class="fas fa-key"></i> Đặt Lại Mật Khẩu</h4>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('password.store') }}">@csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email', $request->email) }}" required>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật Khẩu Mới</label>
                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                    </div>

                    <button type="submit" class="btn btn-info btn-lg w-100 fw-bold">
                        <i class="fas fa-save"></i> Đặt Lại Mật Khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection