<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row">
            <!-- Upload Section -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <h5 class="mb-3">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Tải Ảnh Lên
                        </h5>
                        <form id="uploadForm" enctype="multipart/form-data" data-product-id="{{ $product->id }}">
                            @csrf
                            <div class="upload-area mb-3" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                <p class="mb-2">Kéo thả ảnh vào đây</p>
                                <p class="small mb-3">hoặc</p>
                                <input type="file" id="newImages" name="images[]" class="d-none" accept="image/*" multiple>
                                <button type="button" class="btn btn-light btn-sm" onclick="document.getElementById('newImages').click()">
                                    <i class="fas fa-folder-open me-2"></i>Chọn File
                                </button>
                            </div>
                            <small class="d-block mb-3 opacity-75">
                                <i class="fas fa-info-circle me-1"></i>Tối đa 5 ảnh, mỗi ảnh ≤ 2MB
                            </small>
                            <div id="uploadPreview" class="mb-3"></div>
                            <button type="submit" class="btn btn-light w-100">
                                <i class="fas fa-upload me-2"></i>Tải Lên
                            </button>
                        </form>
                    </div>
                </div>

                @if($availableColors->count() > 0)
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="fas fa-palette me-2"></i>Màu Có Sẵn
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($availableColors as $color)
                            <span class="badge bg-primary px-3 py-2">{{ ucfirst($color) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Images Grid -->
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-images me-2"></i>Ảnh Hiện Tại
                    </h5>
                    <span class="badge bg-primary">{{ $product->images->count() }} ảnh</span>
                </div>

                @if($availableColors->count() == 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Chưa có màu sắc! Hãy tạo variants với màu trước để gán màu cho ảnh.
                </div>
                @endif

                <div class="row g-3" id="currentImages">
                    @if($product->image)
                    <div class="col-md-4 col-6" id="main-image-card">
                        <div class="image-card-modern">
                            <div class="image-wrapper-modern">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Main Image">
                                <div class="image-badge-modern">
                                    <span class="badge bg-primary">Ảnh Chính</span>
                                </div>
                                @if($product->image_color)
                                <div class="image-badge-modern" style="top: 8px; left: auto; right: 8px;">
                                    <span class="badge bg-success">{{ ucfirst($product->image_color) }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="image-controls-modern">
                                <div class="d-flex gap-2 align-items-center">
                                    <select class="form-select form-select-sm main-image-color-select flex-grow-1">
                                        <option value="">-- Chọn màu --</option>
                                        @foreach($availableColors as $color)
                                        <option value="{{ $color }}" {{ $product->image_color == $color ? 'selected' : '' }}>
                                            {{ ucfirst($color) }}
                                        </option>
                                        @endforeach
                                        <option value="__custom__">✨ Màu khác...</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-outline-primary main-image-custom-color-btn" title="Thêm màu tùy chỉnh">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @foreach($product->images as $image)
                    <div class="col-md-4 col-6" id="image-{{ $image->id }}">
                        <div class="image-card-modern">
                            <div class="image-wrapper-modern">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Product Image">
                                
                                <!-- Delete Button (X đỏ ở góc) -->
                                <button type="button" class="delete-btn-corner" onclick="deleteImageQuick({{ $image->id }})" title="Xóa ảnh">
                                    <i class="fas fa-times"></i>
                                </button>
                                
                                @if($image->color)
                                <div class="image-badge-modern">
                                    <span class="badge bg-success">{{ ucfirst($image->color) }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="image-controls-modern">
                                <div class="d-flex gap-2 align-items-center">
                                    <select class="form-select form-select-sm color-select flex-grow-1" data-image-id="{{ $image->id }}">
                                        <option value="">-- Chọn màu --</option>
                                        @foreach($availableColors as $color)
                                        <option value="{{ $color }}" {{ $image->color == $color ? 'selected' : '' }}>
                                            {{ ucfirst($color) }}
                                        </option>
                                        @endforeach
                                        <option value="__custom__">✨ Màu khác...</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-outline-primary custom-color-btn" data-image-id="{{ $image->id }}" title="Thêm màu tùy chỉnh">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if(!$product->image && $product->images->count() == 0)
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có ảnh nào. Hãy tải ảnh lên bên trái.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Upload Area */
.upload-area {
    border: 3px dashed rgba(255,255,255,0.5);
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: rgba(255,255,255,0.8);
    background: rgba(255,255,255,0.1);
}

.upload-area.dragover {
    border-color: white;
    background: rgba(255,255,255,0.2);
    transform: scale(1.02);
}

/* Modern Image Card */
.image-card-modern {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
    height: 100%;
}

.image-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-color: #667eea;
}

.image-wrapper-modern {
    position: relative;
    width: 100%;
    padding-top: 100%;
    overflow: hidden;
    background: #f8f9fa;
}

.image-wrapper-modern img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-badge-modern {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 10;
}

/* Delete Button - X đỏ ở góc */
.delete-btn-corner {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 32px;
    height: 32px;
    background: #dc3545;
    color: white;
    border: 2px solid white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    opacity: 0;
}

.image-card-modern:hover .delete-btn-corner {
    opacity: 1;
}

.delete-btn-corner:hover {
    background: #c82333;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.image-controls-modern {
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

/* Upload Preview */
.upload-preview-item {
    display: inline-block;
    margin: 5px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid rgba(255,255,255,0.3);
}

.upload-preview-item img {
    width: 60px;
    height: 60px;
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
document.addEventListener('DOMContentLoaded', function() {
    // Get fresh references to elements
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('newImages');
    const uploadForm = document.getElementById('uploadForm');
    const uploadPreview = document.getElementById('uploadPreview');

    if (!uploadArea || !fileInput || !uploadForm) {
        console.error('Required elements not found');
        return;
    }

    // Preview images function
    function previewImages() {
        uploadPreview.innerHTML = '';
        
        const files = Array.from(fileInput.files);
        if (files.length > 0) {
            uploadPreview.innerHTML = '<p class="text-white small mb-2">Đã chọn ' + files.length + ' ảnh:</p>';
        }
        
        files.forEach((file, index) => {
            if (index < 5) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'upload-preview-item';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    uploadPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Click to select files
    uploadArea.addEventListener('click', function(e) {
        if (e.target.tagName !== 'BUTTON') {
            fileInput.click();
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadArea.classList.remove('dragover');
        
        const dt = e.dataTransfer;
        fileInput.files = dt.files;
        previewImages();
    });

    // File input change
    fileInput.addEventListener('change', previewImages);

    // Upload form submit
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const productId = this.dataset.productId;
        
        console.log('Product ID:', productId);
        console.log('Files count:', fileInput.files.length);
        
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Vui lòng chọn ít nhất 1 ảnh!');
            return;
        }
        
        // Create FormData and append files manually
        const formData = new FormData();
        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append('images[]', fileInput.files[i]);
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tải...';
        
        const uploadUrl = `/admin/products/${productId}/images`;
        console.log('Upload URL:', uploadUrl);
        
        fetch(uploadUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.json().then(data => {
                    console.error('Error data:', data);
                    throw new Error(data.message || 'Có lỗi xảy ra');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success data:', data);
            if (data.success) {
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Thành công!';
                submitBtn.classList.remove('btn-light');
                submitBtn.classList.add('btn-success');
                
                alert(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Lỗi: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Tải Lên';
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-light');
        });
    });

    // Color assignment
    document.querySelectorAll('.color-select').forEach(select => {
        select.addEventListener('change', function() {
            const imageId = this.dataset.imageId;
            let color = this.value;
            
            // If custom color option selected, show prompt
            if (color === '__custom__') {
                const customColor = prompt('Nhập tên màu (ví dụ: red, blue, navy, beige, v.v.):');
                if (customColor && customColor.trim()) {
                    color = customColor.trim().toLowerCase();
                    
                    // Add new option to select if not exists
                    const existingOption = Array.from(this.options).find(opt => opt.value === color);
                    if (!existingOption) {
                        const newOption = document.createElement('option');
                        newOption.value = color;
                        newOption.textContent = color.charAt(0).toUpperCase() + color.slice(1);
                        // Insert before the "Màu khác..." option
                        this.insertBefore(newOption, this.options[this.options.length - 1]);
                    }
                    this.value = color;
                } else {
                    this.value = '';
                    return;
                }
            }
            
            this.style.opacity = '0.6';
            this.disabled = true;
            
            fetch(`/admin/images/${imageId}/assign-color`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ color: color || null })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.add('success-pulse');
                    setTimeout(() => this.classList.remove('success-pulse'), 600);
                    
                    // Update badge
                    const imageCard = this.closest('.image-card-modern');
                    const badge = imageCard.querySelector('.image-badge-modern');
                    if (color) {
                        if (badge) {
                            badge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                        } else {
                            const wrapper = imageCard.querySelector('.image-wrapper-modern');
                            const newBadge = document.createElement('div');
                            newBadge.className = 'image-badge-modern';
                            newBadge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                            wrapper.appendChild(newBadge);
                        }
                    } else if (badge && !badge.querySelector('.badge-primary')) {
                        badge.remove();
                    }
                }
            })
            .finally(() => {
                this.style.opacity = '1';
                this.disabled = false;
            });
        });
    });
    
    // Custom color button click
    document.querySelectorAll('.custom-color-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            const select = document.querySelector(`.color-select[data-image-id="${imageId}"]`);
            
            const customColor = prompt('Nhập tên màu mới (ví dụ: red, blue, navy, beige, pink, v.v.):');
            if (!customColor || !customColor.trim()) return;
            
            const color = customColor.trim().toLowerCase();
            
            // Add new option to select if not exists
            const existingOption = Array.from(select.options).find(opt => opt.value === color);
            if (!existingOption) {
                const newOption = document.createElement('option');
                newOption.value = color;
                newOption.textContent = color.charAt(0).toUpperCase() + color.slice(1);
                // Insert before the "Màu khác..." option
                select.insertBefore(newOption, select.options[select.options.length - 1]);
            }
            
            // Set the value and trigger change
            select.value = color;
            select.style.opacity = '0.6';
            select.disabled = true;
            
            fetch(`/admin/images/${imageId}/assign-color`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ color: color })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    select.classList.add('success-pulse');
                    setTimeout(() => select.classList.remove('success-pulse'), 600);
                    
                    // Update badge
                    const imageCard = select.closest('.image-card-modern');
                    const badge = imageCard.querySelector('.image-badge-modern');
                    if (badge) {
                        badge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                    } else {
                        const wrapper = imageCard.querySelector('.image-wrapper-modern');
                        const newBadge = document.createElement('div');
                        newBadge.className = 'image-badge-modern';
                        newBadge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                        wrapper.appendChild(newBadge);
                    }
                    
                    alert(`Đã gán màu "${color}" thành công!`);
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra: ' + error.message);
            })
            .finally(() => {
                select.style.opacity = '1';
                select.disabled = false;
            });
        });
    });
    
    // Main image color assignment
    const mainImageColorSelect = document.querySelector('.main-image-color-select');
    if (mainImageColorSelect) {
        mainImageColorSelect.addEventListener('change', function() {
            let color = this.value;
            const productId = document.getElementById('uploadForm').dataset.productId;
            
            // If custom color option selected, show prompt
            if (color === '__custom__') {
                const customColor = prompt('Nhập tên màu (ví dụ: red, blue, navy, beige, v.v.):');
                if (customColor && customColor.trim()) {
                    color = customColor.trim().toLowerCase();
                    
                    // Add new option to select if not exists
                    const existingOption = Array.from(this.options).find(opt => opt.value === color);
                    if (!existingOption) {
                        const newOption = document.createElement('option');
                        newOption.value = color;
                        newOption.textContent = color.charAt(0).toUpperCase() + color.slice(1);
                        // Insert before the "Màu khác..." option
                        this.insertBefore(newOption, this.options[this.options.length - 1]);
                    }
                    this.value = color;
                } else {
                    this.value = '';
                    return;
                }
            }
            
            this.style.opacity = '0.6';
            this.disabled = true;
            
            fetch(`/admin/products/${productId}/assign-main-image-color`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ color: color || null })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.add('success-pulse');
                    setTimeout(() => this.classList.remove('success-pulse'), 600);
                    
                    // Update badge
                    const mainImageCard = document.getElementById('main-image-card');
                    const wrapper = mainImageCard.querySelector('.image-wrapper-modern');
                    let colorBadge = wrapper.querySelector('.image-badge-modern:not(:first-child)');
                    
                    if (color) {
                        if (colorBadge) {
                            colorBadge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                        } else {
                            const newBadge = document.createElement('div');
                            newBadge.className = 'image-badge-modern';
                            newBadge.style.cssText = 'top: 8px; left: auto; right: 8px;';
                            newBadge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                            wrapper.appendChild(newBadge);
                        }
                    } else if (colorBadge) {
                        colorBadge.remove();
                    }
                }
            })
            .finally(() => {
                this.style.opacity = '1';
                this.disabled = false;
            });
        });
    }
    
    // Main image custom color button
    const mainImageCustomBtn = document.querySelector('.main-image-custom-color-btn');
    if (mainImageCustomBtn) {
        mainImageCustomBtn.addEventListener('click', function() {
            const select = document.querySelector('.main-image-color-select');
            const productId = document.getElementById('uploadForm').dataset.productId;
            
            const customColor = prompt('Nhập tên màu mới (ví dụ: red, blue, navy, beige, pink, v.v.):');
            if (!customColor || !customColor.trim()) return;
            
            const color = customColor.trim().toLowerCase();
            
            // Add new option to select if not exists
            const existingOption = Array.from(select.options).find(opt => opt.value === color);
            if (!existingOption) {
                const newOption = document.createElement('option');
                newOption.value = color;
                newOption.textContent = color.charAt(0).toUpperCase() + color.slice(1);
                // Insert before the "Màu khác..." option
                select.insertBefore(newOption, select.options[select.options.length - 1]);
            }
            
            // Set the value
            select.value = color;
            select.style.opacity = '0.6';
            select.disabled = true;
            
            fetch(`/admin/products/${productId}/assign-main-image-color`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ color: color })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    select.classList.add('success-pulse');
                    setTimeout(() => select.classList.remove('success-pulse'), 600);
                    
                    // Update badge
                    const mainImageCard = document.getElementById('main-image-card');
                    const wrapper = mainImageCard.querySelector('.image-wrapper-modern');
                    let colorBadge = wrapper.querySelector('.image-badge-modern:not(:first-child)');
                    
                    if (colorBadge) {
                        colorBadge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                    } else {
                        const newBadge = document.createElement('div');
                        newBadge.className = 'image-badge-modern';
                        newBadge.style.cssText = 'top: 8px; left: auto; right: 8px;';
                        newBadge.innerHTML = `<span class="badge bg-success">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                        wrapper.appendChild(newBadge);
                    }
                    
                    alert(`Đã gán màu "${color}" cho ảnh chính thành công!`);
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra: ' + error.message);
            })
            .finally(() => {
                select.style.opacity = '1';
                select.disabled = false;
            });
        });
    }
});

// Quick delete with X button (global function)
function deleteImageQuick(imageId) {
    if (!confirm('Xóa ảnh này?')) return;
    
    const imageCard = document.getElementById(`image-${imageId}`);
    const productId = document.getElementById('uploadForm').dataset.productId;
    imageCard.style.opacity = '0.5';
    
    fetch(`/admin/products/${productId}/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            imageCard.style.transition = 'all 0.3s ease';
            imageCard.style.transform = 'scale(0)';
            setTimeout(() => imageCard.remove(), 300);
        } else {
            alert('Lỗi: ' + data.message);
            imageCard.style.opacity = '1';
        }
    })
    .catch(error => {
        alert('Có lỗi xảy ra');
        imageCard.style.opacity = '1';
    });
}
</script>
