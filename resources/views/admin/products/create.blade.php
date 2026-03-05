@extends('layouts.admin')

@section('page_title', 'Thêm Sản Phẩm Mới')
@section('header_icon', 'fas fa-plus-circle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
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

            @if(session('warning') && session('show_duplicate_confirm'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-circle me-2"></i>Cảnh Báo Trùng Lặp:</strong>
                    <p class="mb-3 mt-2">{{ session('warning') }}</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" onclick="confirmDuplicate()">
                            <i class="fas fa-check me-1"></i>Vâng, Tạo Sản Phẩm Mới
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times me-1"></i>Hủy Bỏ
                        </a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="quantity" value="0">
                
                <!-- Basic Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Cơ Bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Nhập tên sản phẩm" value="{{ old('name') }}" required />
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Mô Tả Sản Phẩm</label>
                                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" placeholder="Nhập mô tả chi tiết về sản phẩm">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label fw-bold">Danh Mục <span class="text-danger">*</span></label>
                                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="price" class="form-label fw-bold">Giá (VND) <span class="text-danger">*</span></label>
                                    <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" 
                                           placeholder="0" min="0" step="1000" value="{{ old('price', 0) }}" required />
                                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Số lượng sẽ được nhập ở phần Hình Ảnh & Biến Thể bên dưới</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Images & Variants Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Hình Ảnh & Biến Thể</h5>
                        <p class="text-muted small mb-0 mt-2">Mỗi ảnh đại diện cho một màu sắc. Chọn size và nhập số lượng cho từng tổ hợp màu-size.</p>
                    </div>
                    <div class="card-body">
                        <div id="images_container">
                            <!-- Image cards will be added here -->
                        </div>
                        
                        <button type="button" class="btn btn-primary" onclick="addImageCard()">
                            <i class="fas fa-plus me-2"></i>Thêm Ảnh & Màu Sắc
                        </button>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Hướng dẫn:</strong> Mỗi ảnh đại diện cho một màu. Chọn size có sẵn và nhập số lượng cho từng size của màu đó.
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Cài Đặt SEO</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="seo_title" class="form-label">SEO Title</label>
                            <input type="text" id="seo_title" name="seo_title" class="form-control" 
                                   placeholder="Tiêu đề SEO (để trống sẽ dùng tên sản phẩm)" value="{{ old('seo_title') }}">
                            <small class="text-muted">Tối đa 60 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="seo_description" class="form-label">SEO Description</label>
                            <textarea id="seo_description" name="seo_description" class="form-control" rows="3" 
                                      placeholder="Mô tả SEO">{{ old('seo_description') }}</textarea>
                            <small class="text-muted">Tối đa 160 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" 
                                   placeholder="từ khóa 1, từ khóa 2, từ khóa 3" value="{{ old('meta_keywords') }}">
                            <small class="text-muted">Các từ khóa cách nhau bằng dấu phẩy</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay Lại
                    </a>
                    <div>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                            <i class="fas fa-save me-2"></i>Lưu Nháp
                        </button>
                        <button type="submit" name="action" value="publish" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Xuất Bản
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Hướng Dẫn</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-image me-2 text-primary"></i>Hình Ảnh</h6>
                        <ul class="small text-muted">
                            <li>Ảnh chính nên có tỷ lệ 1:1 (vuông)</li>
                            <li>Độ phân giải tối thiểu 800x800px</li>
                            <li>Định dạng JPG hoặc PNG</li>
                            <li>Kích thước file không quá 2MB</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-layer-group me-2 text-success"></i>Biến Thể</h6>
                        <ul class="small text-muted">
                            <li>Mỗi biến thể cần có SKU riêng</li>
                            <li>Giá biến thể sẽ ghi đè giá chính</li>
                            <li>Quản lý tồn kho theo từng biến thể</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="fas fa-search me-2 text-info"></i>SEO</h6>
                        <ul class="small text-muted">
                            <li>Title nên chứa từ khóa chính</li>
                            <li>Description mô tả ngắn gọn sản phẩm</li>
                            <li>Keywords giúp tìm kiếm tốt hơn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    min-height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-upload-area:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.image-upload-slot {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: #f8f9fa;
}

.image-upload-slot:hover {
    border-color: #007bff;
    background-color: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.image-preview-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.preview-img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
}

.btn-delete-image {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #dc3545;
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
}

.btn-delete-image:hover {
    background: #c82333;
    transform: scale(1.1);
}

.additional-image-card {
    position: relative;
    border: 2px solid #dee2e6;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
}

.additional-image-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.additional-image-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.image-controls {
    padding: 15px;
    background: #f8f9fa;
}

.variant-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background-color: #f8f9fa;
}
</style>

<style>
.image-card {
    border: 2px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.image-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.image-preview {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
}

.size-quantity-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    padding: 10px;
    background: white;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.size-badge {
    min-width: 50px;
    text-align: center;
    font-size: 14px;
    padding: 6px 12px;
}
</style>

<script>
let imageIndex = 0;

function addImageCard() {
    const container = document.getElementById('images_container');
    const index = imageIndex++;
    
    const card = document.createElement('div');
    card.className = 'image-card';
    card.id = `image-card-${index}`;
    card.dataset.index = index;
    
    card.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 card-title"><i class="fas fa-image me-2"></i>Ảnh <span class="image-number">1</span></h6>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeImageCard(${index})">
                <i class="fas fa-trash"></i> Xóa
            </button>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Chọn Ảnh</label>
                <input type="file" name="images[${index}][file]" class="form-control" accept="image/*" 
                       onchange="previewImageCard(this, ${index})" required>
                <div id="preview-${index}" class="mt-2"></div>
            </div>
            
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-bold">Màu Sắc <span class="text-danger">*</span></label>
                    <input type="text" name="images[${index}][color]" class="form-control" 
                           placeholder="Ví dụ: Đỏ, Trắng, Đen..." required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Số Lượng Theo Size</label>
                    <small class="text-muted d-block mb-2">Nhập số lượng cho từng size (để 0 nếu không có size đó)</small>
                    <small class="text-info d-block mb-2"><i class="fas fa-info-circle"></i> Lưu ý: Sản phẩm thuộc danh mục "Phụ kiện" sẽ chỉ cần nhập số lượng tổng, không cần size</small>
                    
                    <div id="size-quantities-${index}" class="mt-3">
                        <div class="size-quantity-row">
                            <span class="badge bg-primary size-badge">S</span>
                            <label class="form-label mb-0 me-2">Số lượng:</label>
                            <input type="number" name="images[${index}][sizes][S]" 
                                   class="form-control size-input" min="0" value="0" style="width: 120px;">
                        </div>
                        <div class="size-quantity-row">
                            <span class="badge bg-primary size-badge">M</span>
                            <label class="form-label mb-0 me-2">Số lượng:</label>
                            <input type="number" name="images[${index}][sizes][M]" 
                                   class="form-control size-input" min="0" value="0" style="width: 120px;">
                        </div>
                        <div class="size-quantity-row">
                            <span class="badge bg-primary size-badge">L</span>
                            <label class="form-label mb-0 me-2">Số lượng:</label>
                            <input type="number" name="images[${index}][sizes][L]" 
                                   class="form-control size-input" min="0" value="0" style="width: 120px;">
                        </div>
                        <div class="size-quantity-row">
                            <span class="badge bg-primary size-badge">XL</span>
                            <label class="form-label mb-0 me-2">Số lượng:</label>
                            <input type="number" name="images[${index}][sizes][XL]" 
                                   class="form-control size-input" min="0" value="0" style="width: 120px;">
                        </div>
                        <div class="size-quantity-row">
                            <span class="badge bg-primary size-badge">XXL</span>
                            <label class="form-label mb-0 me-2">Số lượng:</label>
                            <input type="number" name="images[${index}][sizes][XXL]" 
                                   class="form-control size-input" min="0" value="0" style="width: 120px;">
                        </div>
                    </div>
                    
                    <div id="no-size-quantity-${index}" class="mt-3" style="display: none;">
                        <div class="size-quantity-row">
                            <span class="badge bg-success size-badge">Tổng</span>
                            <label class="form-label mb-0 me-2">Số lượng:</label>
                            <input type="number" name="images[${index}][quantity]" 
                                   class="form-control no-size-input" min="0" value="0" style="width: 120px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(card);
    updateImageNumbers();
    
    // Apply size toggle for new card
    toggleSizeInputs();
}

function removeImageCard(index) {
    const card = document.getElementById(`image-card-${index}`);
    if (card && confirm('Xóa ảnh này?')) {
        card.remove();
        updateImageNumbers();
    }
}

function updateImageNumbers() {
    const cards = document.querySelectorAll('.image-card');
    cards.forEach((card, index) => {
        const numberSpan = card.querySelector('.image-number');
        if (numberSpan) {
            numberSpan.textContent = index + 1;
        }
    });
}

function previewImageCard(input, index) {
    const preview = document.getElementById(`preview-${index}`);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="image-preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Add first image card on page load
document.addEventListener('DOMContentLoaded', function() {
    addImageCard();
    
    // Listen to category change
    const categorySelect = document.getElementById('category_id');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            toggleSizeInputs();
        });
        
        // Check on page load
        toggleSizeInputs();
    }
});

function toggleSizeInputs() {
    const categorySelect = document.getElementById('category_id');
    const selectedOption = categorySelect.options[categorySelect.selectedIndex];
    const categoryName = selectedOption.text.toLowerCase();
    
    // Check if category is "Phụ kiện" or contains "accessory"
    const isAccessory = categoryName.includes('phụ kiện') || 
                       categoryName.includes('accessory') || 
                       categoryName.includes('accessories');
    
    // Toggle all size inputs in all image cards
    document.querySelectorAll('.image-card').forEach(card => {
        const index = card.dataset.index;
        const sizeContainer = document.getElementById(`size-quantities-${index}`);
        const noSizeContainer = document.getElementById(`no-size-quantity-${index}`);
        
        if (sizeContainer && noSizeContainer) {
            if (isAccessory) {
                // Hide size inputs, show single quantity input
                sizeContainer.style.display = 'none';
                noSizeContainer.style.display = 'block';
                
                // Disable size inputs so they won't be submitted
                sizeContainer.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                });
                noSizeContainer.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                });
            } else {
                // Show size inputs, hide single quantity input
                sizeContainer.style.display = 'block';
                noSizeContainer.style.display = 'none';
                
                // Enable size inputs
                sizeContainer.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                });
                noSizeContainer.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                });
            }
        }
    });
}
</script>
@endsection