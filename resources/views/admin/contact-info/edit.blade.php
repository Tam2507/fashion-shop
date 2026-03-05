@extends('layouts.admin')

@section('title', 'Quản Lý Thông Tin Liên Hệ')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="fas fa-address-card"></i> Quản Lý Thông Tin Liên Hệ</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.contact-info.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-map-marker-alt"></i> Địa Chỉ</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ *</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $contactInfo->address) }}" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quận/Thành phố</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $contactInfo->city) }}">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Quốc gia *</label>
                            <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $contactInfo->country) }}" required>
                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-phone"></i> Điện Thoại</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hotline *</label>
                            <input type="text" name="hotline" class="form-control @error('hotline') is-invalid @enderror" value="{{ old('hotline', $contactInfo->hotline) }}" required>
                            @error('hotline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại mua hàng online</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $contactInfo->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian hoạt động</label>
                            <input type="text" name="working_hours" class="form-control @error('working_hours') is-invalid @enderror" value="{{ old('working_hours', $contactInfo->working_hours) }}" placeholder="8:00 - 22:00 (T2-CN)">
                            @error('working_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-envelope"></i> Email</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email chính *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $contactInfo->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email hỗ trợ</label>
                            <input type="email" name="support_email" class="form-control @error('support_email') is-invalid @enderror" value="{{ old('support_email', $contactInfo->support_email) }}">
                            @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-clock"></i> Giờ Làm Việc</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thứ 2 - Thứ 6</label>
                            <input type="text" name="weekday_hours" class="form-control @error('weekday_hours') is-invalid @enderror" value="{{ old('weekday_hours', $contactInfo->weekday_hours) }}" placeholder="Thứ 2 - Thứ 6: 8:00 - 18:00">
                            @error('weekday_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thứ 7 - Chủ nhật</label>
                            <input type="text" name="weekend_hours" class="form-control @error('weekend_hours') is-invalid @enderror" value="{{ old('weekend_hours', $contactInfo->weekend_hours) }}" placeholder="Thứ 7 - Chủ nhật: 9:00 - 17:00">
                            @error('weekend_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú lễ tết</label>
                            <input type="text" name="holiday_note" class="form-control @error('holiday_note') is-invalid @enderror" value="{{ old('holiday_note', $contactInfo->holiday_note) }}" placeholder="Lễ tết: Theo thông báo">
                            @error('holiday_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fas fa-map"></i> Bản Đồ Google Maps</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">URL Embed Google Maps</label>
                            <textarea name="map_embed_url" id="map_embed_url" class="form-control @error('map_embed_url') is-invalid @enderror" rows="3" placeholder="Paste toàn bộ code iframe hoặc chỉ URL...">{{ old('map_embed_url', $contactInfo->map_embed_url) }}</textarea>
                            @error('map_embed_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="alert alert-info mt-2">
                                <strong><i class="fas fa-info-circle"></i> Bạn có thể:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Paste toàn bộ code <code>&lt;iframe&gt;...&lt;/iframe&gt;</code> (hệ thống tự động lấy URL)</li>
                                    <li>Hoặc chỉ paste URL: <code>https://www.google.com/maps/embed?pb=...</code></li>
                                </ul>
                            </div>
                        </div>

                        <script>
                        document.getElementById('map_embed_url').addEventListener('blur', function() {
                            let value = this.value.trim();
                            
                            // Nếu paste cả iframe tag, extract URL từ src
                            if (value.includes('<iframe') && value.includes('src=')) {
                                const srcMatch = value.match(/src=["']([^"']+)["']/);
                                if (srcMatch && srcMatch[1]) {
                                    this.value = srcMatch[1];
                                    // Show success message
                                    const alert = document.createElement('div');
                                    alert.className = 'alert alert-success mt-2';
                                    alert.innerHTML = '<i class="fas fa-check"></i> Đã tự động lấy URL từ iframe!';
                                    this.parentElement.appendChild(alert);
                                    setTimeout(() => alert.remove(), 3000);
                                }
                            }
                        });
                        </script>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
