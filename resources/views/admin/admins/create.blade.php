@extends('layouts.admin')

@section('page_title', 'Thêm Tài Khoản Admin Mới')
@section('header_icon', 'fas fa-user-plus')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h1 class="h3 mb-4"><i class="fas fa-user-plus me-2"></i> Tạo Tài Khoản Admin Mới</h1>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Lỗi Xác Thực:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-lg">
                <div class="card-header" style="background: linear-gradient(135deg, #8B3A3A 0%, #A85252 100%); color: white; border-radius: 8px 8px 0 0; border: none;">
                    <h5 class="mb-0">Thông Tin Tài Khoản</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.admins.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Họ và Tên</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                placeholder="Nhập họ và tên"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                placeholder="Nhập email"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mật Khẩu</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                placeholder="Nhập mật khẩu (tối thiểu 8 ký tự)"
                                required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                placeholder="Xác nhận mật khẩu"
                                required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-dark btn-lg">
                                <i class="fas fa-check me-2"></i> Tạo Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
