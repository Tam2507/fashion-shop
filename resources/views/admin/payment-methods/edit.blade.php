@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Phương Thức Thanh Toán')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Chỉnh Sửa Phương Thức Thanh Toán</h1>
    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.payment-methods.update', $paymentMethod) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên phương thức *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Mã phương thức *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $paymentMethod->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mã duy nhất, ví dụ: visa, mastercard, atm</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        @if($paymentMethod->logo)
                            <div class="mb-2" id="currentLogo">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label mb-0">Logo hiện tại:</label>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="changeLogo()">
                                        <i class="fas fa-edit"></i> Thay đổi
                                    </button>
                                </div>
                                <img src="/storage/{{ $paymentMethod->logo }}" alt="{{ $paymentMethod->name }}" 
                                     class="img-thumbnail" style="max-height: 100px; max-width: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                               id="logo" name="logo" accept="image/*" onchange="previewImage(this)" 
                               {{ $paymentMethod->logo ? 'style=display:none;' : '' }}>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chấp nhận: JPG, PNG, SVG. Tối đa 2MB. {{ $paymentMethod->logo ? 'Để trống nếu không muốn thay đổi.' : '' }}</div>
                        
                        <!-- New Logo Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label mb-0">Logo mới:</label>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePreview()">
                                    <i class="fas fa-times"></i> Hủy thay đổi
                                </button>
                            </div>
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px; max-width: 200px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $paymentMethod->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Vị trí hiển thị *</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', $paymentMethod->position) }}" min="0" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt phương thức
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập Nhật Phương Thức
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
                <h5><i class="fas fa-eye"></i> Xem trước</h5>
            </div>
            <div class="card-body text-center">
                <div class="payment-preview p-3 border rounded">
                    @if($paymentMethod->logo)
                        <img src="/storage/{{ $paymentMethod->logo }}" alt="{{ $paymentMethod->name }}" 
                             style="max-height: 60px; max-width: 120px; object-fit: contain;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 120px; height: 60px; border-radius: 4px;">
                            <i class="fas fa-credit-card text-muted"></i>
                        </div>
                    @endif
                    <h6 class="mt-2 mb-1">{{ $paymentMethod->name }}</h6>
                    @if($paymentMethod->description)
                        <small class="text-muted">{{ $paymentMethod->description }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.previewImage = function(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const currentLogo = document.getElementById('currentLogo');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
                if (currentLogo) {
                    currentLogo.style.opacity = '0.5';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.removePreview = function() {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const fileInput = document.getElementById('logo');
        const currentLogo = document.getElementById('currentLogo');
        
        preview.style.display = 'none';
        previewImg.src = '';
        fileInput.value = '';
        fileInput.style.display = 'none';
        
        if (currentLogo) {
            currentLogo.style.opacity = '1';
        }
    }

    window.changeLogo = function() {
        const fileInput = document.getElementById('logo');
        fileInput.style.display = 'block';
        fileInput.click();
    }
});
</script>
@endsection