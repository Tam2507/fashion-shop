@extends('layouts.admin')

@section('title', 'Tạo Tính Năng Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus"></i> Tạo Tính Năng Mới</h1>
    <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.features.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Tiêu đề *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="position" class="form-label">Vị trí *</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', 0) }}" min="0" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (Font Awesome class) *</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" value="{{ old('icon', 'fas fa-star') }}" required
                               placeholder="fas fa-shipping-fast">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Ví dụ: fas fa-shipping-fast, fas fa-undo-alt, fas fa-shield-alt, fas fa-headset</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="background_color" class="form-label">Màu nền icon</label>
                            <input type="color" class="form-control form-control-color @error('background_color') is-invalid @enderror" 
                                   id="background_color" name="background_color" value="{{ old('background_color', '#8B3A3A') }}">
                            @error('background_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="icon_color" class="form-label">Màu icon</label>
                            <input type="color" class="form-control form-control-color @error('icon_color') is-invalid @enderror" 
                                   id="icon_color" name="icon_color" value="{{ old('icon_color', '#FFFFFF') }}">
                            @error('icon_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Kích hoạt tính năng
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Tạo Tính Năng
                        </button>
                        <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
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
                <div class="feature-preview">
                    <div class="feature-icon mb-3" id="preview-icon" 
                         style="width: 80px; height: 80px; background-color: #8B3A3A; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 2rem;">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 id="preview-title">Tiêu đề tính năng</h5>
                    <p class="text-muted" id="preview-description">Mô tả tính năng sẽ hiển thị ở đây</p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Gợi ý Icon</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <i class="fas fa-shipping-fast" style="font-size: 1.5rem; color: #8B3A3A;"></i>
                        <br><small>fas fa-shipping-fast</small>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="fas fa-undo-alt" style="font-size: 1.5rem; color: #8B3A3A;"></i>
                        <br><small>fas fa-undo-alt</small>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="fas fa-shield-alt" style="font-size: 1.5rem; color: #8B3A3A;"></i>
                        <br><small>fas fa-shield-alt</small>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="fas fa-headset" style="font-size: 1.5rem; color: #8B3A3A;"></i>
                        <br><small>fas fa-headset</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const iconInput = document.getElementById('icon');
    const backgroundColorInput = document.getElementById('background_color');
    const iconColorInput = document.getElementById('icon_color');
    
    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const previewIcon = document.getElementById('preview-icon');
    const previewIconElement = previewIcon.querySelector('i');
    
    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Tiêu đề tính năng';
        previewDescription.textContent = descriptionInput.value || 'Mô tả tính năng sẽ hiển thị ở đây';
        previewIconElement.className = iconInput.value || 'fas fa-star';
        previewIcon.style.backgroundColor = backgroundColorInput.value;
        previewIcon.style.color = iconColorInput.value;
    }
    
    titleInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);
    backgroundColorInput.addEventListener('input', updatePreview);
    iconColorInput.addEventListener('input', updatePreview);
});
</script>
@endsection