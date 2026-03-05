@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Bài Viết')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chỉnh Sửa Bài Viết</h1>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông Tin Bài Viết</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $post->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL)</label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $post->slug) }}"
                                   placeholder="Để trống để tự động tạo từ tiêu đề">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Ví dụ: bai-viet-moi-nhat</small>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Mô Tả Ngắn</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" 
                                      name="excerpt" 
                                      rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mô tả ngắn hiển thị trong danh sách bài viết</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội Dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="15" 
                                      required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Cài Đặt</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Ảnh Đại Diện</label>
                            
                            @if($post->featured_image)
                                <div class="mb-2" id="current-featured-image">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                             alt="{{ $post->title }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px;">
                                        <button type="button" 
                                                class="btn btn-danger position-absolute" 
                                                onclick="removeCurrentFeaturedImage()"
                                                style="top: 5px; right: 5px; z-index: 10; width: 24px; height: 24px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            ×
                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_featured_image" id="remove-featured-image" value="0">
                            @endif
                            
                            <input type="file" 
                                   class="form-control @error('featured_image') is-invalid @enderror" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   onchange="previewImage(this, 'featured-preview')">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="featured-preview" class="mt-2"></div>
                            <small class="text-muted">Kích thước tối đa: 5MB. Định dạng: JPG, PNG, GIF, WEBP. Để trống nếu không muốn thay đổi.</small>
                        </div>

                        <!-- Existing Post Images -->
                        @if($post->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Ảnh Bài Viết Hiện Tại</label>
                                <div class="row g-2" id="existing-images">
                                    @foreach($post->images as $image)
                                        <div class="col-6" id="existing-image-{{ $image->id }}">
                                            <div class="position-relative" style="margin-bottom: 10px;">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 100%; height: 100px; object-fit: cover;">
                                                <button type="button" 
                                                        class="btn btn-danger position-absolute" 
                                                        onclick="removeExistingImage({{ $image->id }})"
                                                        style="top: 5px; right: 5px; z-index: 10; width: 24px; height: 24px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                                    ×
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="remove_images[]" id="remove-images-input" value="">
                            </div>
                        @endif

                        <!-- New Post Images -->
                        <div class="mb-3">
                            <label for="post_images" class="form-label">Thêm Ảnh Mới (Nhiều Ảnh)</label>
                            <input type="file" 
                                   class="form-control @error('post_images.*') is-invalid @enderror" 
                                   id="post_images" 
                                   name="post_images[]" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   multiple
                                   onchange="previewMultipleImages(this)">
                            @error('post_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="images-preview" class="mt-2 row g-2"></div>
                            <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc. Mỗi ảnh tối đa 5MB. Định dạng: JPG, PNG, GIF, WEBP</small>
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">Thứ Tự Hiển Thị <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('display_order') is-invalid @enderror" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', $post->display_order) }}" 
                                   min="0" 
                                   required>
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_published" 
                                       name="is_published" 
                                       {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Xuất Bản
                                </label>
                            </div>
                            <small class="text-muted">Bài viết sẽ hiển thị công khai</small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <strong>Tác giả:</strong> {{ $post->author->name }}<br>
                                <strong>Ngày tạo:</strong> {{ $post->created_at->format('d/m/Y H:i') }}<br>
                                <strong>Cập nhật:</strong> {{ $post->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Cập Nhật Bài Viết
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const imagesToRemove = [];

// Remove current featured image
function removeCurrentFeaturedImage() {
    if (confirm('Xóa ảnh đại diện hiện tại?')) {
        document.getElementById('current-featured-image').style.display = 'none';
        document.getElementById('remove-featured-image').value = '1';
    }
}

// Remove existing image
function removeExistingImage(imageId) {
    if (confirm('Xóa ảnh này?')) {
        // Hide the image
        document.getElementById(`existing-image-${imageId}`).style.display = 'none';
        
        // Add to remove list
        imagesToRemove.push(imageId);
        
        // Update hidden input
        const form = document.querySelector('form');
        imagesToRemove.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_images[]';
            input.value = id;
            form.appendChild(input);
        });
    }
}

// Preview single image
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
                    <button type="button" class="btn btn-danger position-absolute" 
                            onclick="clearFeaturedImage('${previewId}')" 
                            style="top: 5px; right: 5px; z-index: 10; width: 24px; height: 24px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                        ×
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Clear featured image
function clearFeaturedImage(previewId) {
    document.getElementById('featured_image').value = '';
    document.getElementById(previewId).innerHTML = '';
}

// Preview multiple images
function previewMultipleImages(input) {
    const preview = document.getElementById('images-preview');
    preview.innerHTML = '';
    
    if (input.files) {
        const dataTransfer = new DataTransfer();
        
        Array.from(input.files).forEach((file, index) => {
            dataTransfer.items.add(file);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-6';
                col.dataset.fileIndex = index;
                col.innerHTML = `
                    <div class="position-relative" style="margin-bottom: 10px;">
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                        <span class="badge bg-primary position-absolute m-1" style="top: 0; left: 0; z-index: 10;">${index + 1}</span>
                        <button type="button" class="btn btn-danger position-absolute" 
                                onclick="removePreviewImage(${index})" 
                                style="top: 5px; right: 5px; z-index: 10; width: 24px; height: 24px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            ×
                        </button>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
        
        input.files = dataTransfer.files;
    }
}

// Remove preview image
function removePreviewImage(index) {
    const input = document.getElementById('post_images');
    const preview = document.getElementById('images-preview');
    
    const previewItem = preview.querySelector(`[data-file-index="${index}"]`);
    if (previewItem) {
        previewItem.remove();
    }
    
    const dataTransfer = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dataTransfer.items.add(file);
        }
    });
    
    input.files = dataTransfer.files;
    
    preview.innerHTML = '';
    Array.from(input.files).forEach((file, newIndex) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-6';
            col.dataset.fileIndex = newIndex;
            col.innerHTML = `
                <div class="position-relative" style="margin-bottom: 10px;">
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 100px; object-fit: cover;">
                    <span class="badge bg-primary position-absolute m-1" style="top: 0; left: 0; z-index: 10;">${newIndex + 1}</span>
                    <button type="button" class="btn btn-danger position-absolute" 
                            onclick="removePreviewImage(${newIndex})" 
                            style="top: 5px; right: 5px; z-index: 10; width: 24px; height: 24px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                        ×
                    </button>
                </div>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection
