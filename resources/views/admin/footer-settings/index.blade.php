@extends('layouts.admin')

@section('title', 'Cài Đặt Footer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-cog"></i> Cài Đặt Footer</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('admin.footer-settings.update') }}">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Company Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-building"></i> Thông Tin Công Ty</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">Tên công ty *</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                   id="company_name" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="business_license" class="form-label">Giấy phép kinh doanh</label>
                            <input type="text" class="form-control @error('business_license') is-invalid @enderror" 
                                   id="business_license" name="business_license" value="{{ old('business_license', $settings->business_license) }}">
                            @error('business_license')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="company_description" class="form-label">Mô tả công ty</label>
                        <textarea class="form-control @error('company_description') is-invalid @enderror" 
                                  id="company_description" name="company_description" rows="3">{{ old('company_description', $settings->company_description) }}</textarea>
                        @error('company_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address', $settings->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $settings->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="hotline" class="form-label">Hotline</label>
                            <input type="text" class="form-control @error('hotline') is-invalid @enderror" 
                                   id="hotline" name="hotline" value="{{ old('hotline', $settings->hotline) }}">
                            @error('hotline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $settings->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="working_hours" class="form-label">Giờ làm việc</label>
                        <textarea class="form-control @error('working_hours') is-invalid @enderror" 
                                  id="working_hours" name="working_hours" rows="2">{{ old('working_hours', $settings->working_hours) }}</textarea>
                        @error('working_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-share-alt"></i> Mạng Xã Hội</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="social_facebook" class="form-label">Facebook</label>
                            <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" 
                                   id="social_facebook" name="social_facebook" value="{{ old('social_facebook', $settings->social_facebook) }}" 
                                   placeholder="https://facebook.com/yourpage">
                            @error('social_facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="social_instagram" class="form-label">Instagram</label>
                            <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" 
                                   id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $settings->social_instagram) }}" 
                                   placeholder="https://instagram.com/yourpage">
                            @error('social_instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="social_youtube" class="form-label">YouTube</label>
                            <input type="url" class="form-control @error('social_youtube') is-invalid @enderror" 
                                   id="social_youtube" name="social_youtube" value="{{ old('social_youtube', $settings->social_youtube) }}" 
                                   placeholder="https://youtube.com/yourchannel">
                            @error('social_youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="social_tiktok" class="form-label">TikTok</label>
                            <input type="url" class="form-control @error('social_tiktok') is-invalid @enderror" 
                                   id="social_tiktok" name="social_tiktok" value="{{ old('social_tiktok', $settings->social_tiktok) }}" 
                                   placeholder="https://tiktok.com/@yourpage">
                            @error('social_tiktok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-credit-card"></i> Phương Thức Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $paymentMethods = ['visa', 'mastercard', 'jcb', 'atm', 'zalopay'];
                            $selectedMethods = old('payment_methods', $settings->payment_methods ?? []);
                        @endphp
                        
                        @foreach($paymentMethods as $method)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]" 
                                       value="{{ $method }}" id="payment_{{ $method }}"
                                       {{ in_array($method, $selectedMethods) ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_{{ $method }}">
                                    {{ ucfirst($method) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-copyright"></i> Bản Quyền</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="copyright_text" class="form-label">Text bản quyền</label>
                        <input type="text" class="form-control @error('copyright_text') is-invalid @enderror" 
                               id="copyright_text" name="copyright_text" value="{{ old('copyright_text', $settings->copyright_text) }}" 
                               placeholder="© 2026 Fashion Shop - Thời Trang Cao Cấp. All rights reserved.">
                        @error('copyright_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Preview -->
            <div class="card sticky-top">
                <div class="card-header">
                    <h5><i class="fas fa-eye"></i> Xem Trước Footer</h5>
                </div>
                <div class="card-body">
                    <div class="footer-preview bg-dark text-white p-3" style="font-size: 0.8rem;">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h6 class="text-white">{{ $settings->company_name ?: 'Fashion Shop' }}</h6>
                                @if($settings->company_description)
                                    <p class="mb-1">{{ Str::limit($settings->company_description, 100) }}</p>
                                @endif
                                @if($settings->address)
                                    <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ $settings->address }}</p>
                                @endif
                                @if($settings->phone)
                                    <p class="mb-1"><i class="fas fa-phone"></i> {{ $settings->phone }}</p>
                                @endif
                                @if($settings->email)
                                    <p class="mb-0"><i class="fas fa-envelope"></i> {{ $settings->email }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if($settings->payment_methods)
                            <div class="mb-2">
                                <small>Phương thức thanh toán:</small>
                                <div class="d-flex gap-1 mt-1">
                                    @foreach($settings->payment_methods as $method)
                                        <span class="badge bg-secondary">{{ ucfirst($method) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small>{{ $settings->copyright_text ?: '© 2026 Fashion Shop' }}</small>
                            <div class="d-flex gap-2">
                                @if($settings->social_facebook)<i class="fab fa-facebook-f"></i>@endif
                                @if($settings->social_instagram)<i class="fab fa-instagram"></i>@endif
                                @if($settings->social_youtube)<i class="fab fa-youtube"></i>@endif
                                @if($settings->social_tiktok)<i class="fab fa-tiktok"></i>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> Lưu Cài Đặt
        </button>
        <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Khôi Phục
        </button>
    </div>
</form>
@endsection