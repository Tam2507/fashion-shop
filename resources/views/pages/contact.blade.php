@extends('layouts.app')

@section('title', 'Liên Hệ - Fashion Shop')

@section('content')
<div class="container py-5">
    <!-- Google Maps Section -->
    @if($contactInfo && $contactInfo->map_embed_url)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Vị Trí Của Chúng Tôi</h2>
            <div class="map-container">
                <iframe src="{{ $contactInfo->map_embed_url }}" 
                        width="100%" 
                        height="450" 
                        style="border:0; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-5">Liên Hệ Với Chúng Tôi</h1>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-map-marker-alt text-primary"></i> Địa chỉ</h5>
                            <p class="card-text">
                                {{ $contactInfo->address ?? 'Lầu 1-2, 123 Nguyễn Văn Cừ' }}<br>
                                {{ $contactInfo->city ?? 'Quận 1, TP. Hồ Chí Minh' }}<br>
                                {{ $contactInfo->country ?? 'Việt Nam' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-phone text-primary"></i> Điện thoại</h5>
                            <p class="card-text">
                                Hotline: <strong>{{ $contactInfo->hotline ?? '1900.1234' }}</strong><br>
                                @if($contactInfo->phone)
                                Mua hàng online: <strong>{{ $contactInfo->phone }}</strong><br>
                                @endif
                                @if($contactInfo->working_hours)
                                Thời gian: {{ $contactInfo->working_hours }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-envelope text-primary"></i> Email</h5>
                            <p class="card-text">
                                Email: <a href="mailto:{{ $contactInfo->email ?? 'info@fashionshop.vn' }}">{{ $contactInfo->email ?? 'info@fashionshop.vn' }}</a><br>
                                @if($contactInfo->support_email)
                                Hỗ trợ: <a href="mailto:{{ $contactInfo->support_email }}">{{ $contactInfo->support_email }}</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-clock text-primary"></i> Giờ làm việc</h5>
                            <p class="card-text">
                                {{ $contactInfo->weekday_hours ?? 'Thứ 2 - Thứ 6: 8:00 - 18:00' }}<br>
                                {{ $contactInfo->weekend_hours ?? 'Thứ 7 - Chủ nhật: 9:00 - 17:00' }}<br>
                                {{ $contactInfo->holiday_note ?? 'Lễ tết: Theo thông báo' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Gửi tin nhắn cho chúng tôi</h5>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Chủ đề *</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" id="subject" value="{{ old('subject') }}" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung *</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" id="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .map-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .map-container:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .map-container iframe {
        display: block;
    }
</style>
@endsection