@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="p-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-images me-2" style="color: #3498db;"></i>Quản Lý Ảnh Sản Phẩm
                        </h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-box me-1"></i>{{ $product->name }}
                        </p>
                    </div>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay Lại
                    </a>
                </div>
            </div>
        </div>

    @if($availableColors->count() == 0)
    <div class="alert alert-warning mb-4" style="border-left: 4px solid #f39c12; border-radius: 8px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3" style="color: #f39c12;"></i>
            <div>
                <strong>Chưa có màu sắc!</strong>
                <p class="mb-0">Bạn cần tạo variants với màu sắc trước để có thể gán màu cho ảnh.</p>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Upload Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                    <h6 class="mb-0">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Tải Ảnh Lên
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-area mb-3" id="uploadArea" style="border: 3px dashed #cbd5e0; border-radius: 12px; padding: 40px 20px; text-align: center; background: #f8f9fa; cursor: pointer; transition: all 0.3s ease;">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #a0aec0;"></i>
                            <p class="mb-2" style="font-weight: 600; color: #4a5568;">Kéo thả ảnh vào đây</p>
                            <p class="text-muted small mb-3">hoặc nhấn để chọn file</p>
                            <input type="file" id="newImages" name="images[]" class="d-none" accept="image/*" multiple>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('newImages').click()">
                                <i class="fas fa-folder-open me-2"></i>Chọn File
                            </button>
                        </div>
                        
                        <div class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Tối đa 5 ảnh, mỗi ảnh ≤ 2MB
                        </div>
                        
                        <div id="uploadPreview" class="mb-3"></div>
                        
                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px; padding: 12px; font-weight: 600;">
                            <i class="fas fa-upload me-2"></i>Tải Lên
                        </button>
                    </form>
                </div>
            </div>

            @if($availableColors->count() > 0)
            <div class="card shadow-sm border-0 mt-4" style="border-radius: 12px;">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-palette me-2" style="color: #e74c3c;"></i>Màu Có Sẵn
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($availableColors as $color)
                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 8px 16px; border-radius: 20px; font-size: 0.9rem;">
                            {{ ucfirst($color) }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Images Grid -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">
                            <i class="fas fa-images me-2" style="color: #3498db;"></i>Ảnh Hiện Tại
                        </h6>
                        <span class="badge bg-primary">{{ $product->images->count() + ($product->image ? 1 : 0) }} ảnh</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3" id="currentImages">
                        @if($product->image)
                        <div class="col-md-4 col-6">
                            <div class="image-card">
                                <div class="image-wrapper">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="Main Image">
                                    <div class="image-badge">
                                        <span class="badge bg-primary">Ảnh Chính</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @foreach($product->images as $image)
                        <div class="col-md-4 col-6" id="image-{{ $image->id }}">
                            <div class="image-card">
                                <div class="image-wrapper">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid" alt="Product Image">
                                    @if($image->color)
                                    <div class="image-badge">
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ ucfirst($image->color) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="image-controls mt-2">
                                    <label class="form-label small mb-1" style="font-weight: 600; color: #4a5568;">
                                        <i class="fas fa-palette me-1"></i>Màu sắc
                                    </label>
                                    <select class="form-select form-select-sm color-select mb-2" data-image-id="{{ $image->id }}" style="border-radius: 6px;">
                                        <option value="">-- Không gán màu --</option>
                                        @foreach($availableColors as $color)
                                        <option value="{{ $color }}" {{ $image->color == $color ? 'selected' : '' }}>
                                            {{ ucfirst($color) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-sm btn-danger w-100 delete-btn" onclick="deleteImage({{ $image->id }})" style="border-radius: 6px;">
                                        <i class="fas fa-trash-alt me-1"></i>Xóa Ảnh
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(!$product->image && $product->images->count() == 0)
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-images fa-4x mb-3" style="color: #cbd5e0;"></i>
                                <p class="text-muted mb-0">Chưa có ảnh nào. Hãy tải ảnh lên bên trái.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Upload Area Hover Effect */
#uploadArea:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

#uploadArea.dragover {
    border-color: #667eea;
    background: #e6f0ff;
    transform: scale(1.02);
}

/* Image Card Styles */
.image-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
}

.image-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-color: #667eea;
}

.image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 10;
}

.image-controls {
    padding: 12px;
    background: #f8f9fa;
}

.color-select {
    transition: all 0.3s ease;
}

.color-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.delete-btn {
    transition: all 0.3s ease;
}

.delete-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

/* Upload Preview */
.upload-preview-item {
    position: relative;
    display: inline-block;
    margin: 5px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
}

.upload-preview-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    display: block;
}

/* Success Animation */
@keyframes successPulse {
    0% { background-color: #fff; }
    50% { background-color: #d4edda; }
    100% { background-color: #fff; }
}

.success-pulse {
    animation: successPulse 0.6s ease;
}
</style>

<script>
// Drag and drop functionality
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('newImages');

uploadArea.addEventListener('click', () => fileInput.click());

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    previewImages();
});

// Preview selected images
fileInput.addEventListener('change', previewImages);

function previewImages() {
    const preview = document.getElementById('uploadPreview');
    preview.innerHTML = '';
    
    const files = Array.from(fileInput.files);
    if (files.length > 0) {
        preview.innerHTML = '<p class="small text-muted mb-2"><strong>Xem trước:</strong></p>';
    }
    
    files.forEach((file, index) => {
        if (index < 5) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'upload-preview-item';
                div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Upload form submission
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const fileInput = document.getElementById('newImages');
    
    // Check if files are selected
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Vui lòng chọn ít nhất 1 ảnh để tải lên!');
        return;
    }
    
    // Check file count
    if (fileInput.files.length > 5) {
        alert('Chỉ được tải tối đa 5 ảnh cùng lúc!');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tải lên...';
    
    fetch(`/admin/products/{{ $product->id }}/images`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Có lỗi xảy ra');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Thành công!';
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-success');
            
            // Show success alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.card-body').insertBefore(alertDiv, document.getElementById('uploadForm'));
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Lỗi: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Tải Lên';
    });
});

// Color assignment
document.querySelectorAll('.color-select').forEach(select => {
    select.addEventListener('change', function() {
        const imageId = this.dataset.imageId;
        const color = this.value;
        const originalBg = this.style.backgroundColor;
        
        // Show loading state
        this.style.opacity = '0.6';
        this.disabled = true;
        
        fetch(`/admin/images/${imageId}/assign-color`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ color: color || null })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success animation
                this.classList.add('success-pulse');
                setTimeout(() => {
                    this.classList.remove('success-pulse');
                }, 600);
                
                // Update badge
                const imageCard = this.closest('.image-card');
                const imageBadge = imageCard.querySelector('.image-badge');
                if (color) {
                    if (!imageBadge) {
                        const wrapper = imageCard.querySelector('.image-wrapper');
                        const badge = document.createElement('div');
                        badge.className = 'image-badge';
                        badge.innerHTML = `<span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                        wrapper.appendChild(badge);
                    } else {
                        imageBadge.innerHTML = `<span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                    }
                } else if (imageBadge) {
                    imageBadge.remove();
                }
            } else {
                alert('Có lỗi xảy ra khi gán màu');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi gán màu');
        })
        .finally(() => {
            this.style.opacity = '1';
            this.disabled = false;
        });
    });
});

// Delete image function
function deleteImage(imageId) {
    if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
        const imageCard = document.getElementById(`image-${imageId}`);
        imageCard.style.opacity = '0.5';
        
        fetch(`/admin/products/{{ $product->id }}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fade out animation
                imageCard.style.transition = 'all 0.3s ease';
                imageCard.style.transform = 'scale(0)';
                setTimeout(() => {
                    imageCard.remove();
                }, 300);
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
                imageCard.style.opacity = '1';
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi xóa ảnh');
            imageCard.style.opacity = '1';
        });
    }
}
</script>
    </div>
</div>
@endsection
