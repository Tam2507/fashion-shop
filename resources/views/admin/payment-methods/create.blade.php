@extends('layouts.admin')

@section('title', 'Thêm Phương Thức Thanh Toán')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus"></i> Thêm Phương Thức Thanh Toán</h1>
    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên phương thức *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Mã phương thức *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mã duy nhất, ví dụ: visa, mastercard, atm</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                               id="logo" name="logo" accept="image/*" onchange="previewImage(this)">
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chấp nhận: JPG, PNG, SVG. Tối đa 2MB.</div>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label mb-0">Xem trước:</label>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreview()">
                                    <i class="fas fa-times"></i> Xóa
                                </button>
                            </div>
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px; max-width: 200px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Vị trí hiển thị *</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', 0) }}" min="0" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt phương thức
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Tạo Phương Thức
                        </button>
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <h6>Các phương thức phổ biến:</h6>
                <ul class="small">
                    <li><strong>visa:</strong> Thẻ Visa</li>
                    <li><strong>mastercard:</strong> Thẻ MasterCard</li>
                    <li><strong>jcb:</strong> Thẻ JCB</li>
                    <li><strong>atm:</strong> Thẻ ATM nội địa</li>
                    <li><strong>zalopay:</strong> ZaloPay</li>
                </ul>
                
                <h6>Kích thước logo khuyến nghị:</h6>
                <ul class="small">
                    <li>Chiều rộng: 60-120px</li>
                    <li>Chiều cao: 30-60px</li>
                    <li>Định dạng: PNG với nền trong suốt</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
    });
    
    window.previewImage = function(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.removePreview = function() {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const fileInput = document.getElementById('logo');
        
        preview.style.display = 'none';
        previewImg.src = '';
        fileInput.value = '';
    }
});
</script>
@endsection