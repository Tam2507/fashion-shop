

<?php $__env->startSection('title', 'Tạo Bài Viết Mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tạo Bài Viết Mới</h1>
        <a href="<?php echo e(route('admin.posts.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Lỗi Xác Thực:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.posts.store')); ?>" method="POST" enctype="multipart/form-data" id="postForm">
        <?php echo csrf_field(); ?>
        
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
                                   class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="title" 
                                   name="title" 
                                   value="<?php echo e(old('title')); ?>" 
                                   required>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug (URL)</label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="slug" 
                                   name="slug" 
                                   value="<?php echo e(old('slug')); ?>"
                                   placeholder="Để trống để tự động tạo từ tiêu đề">
                            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Ví dụ: bai-viet-moi-nhat</small>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Mô Tả Ngắn</label>
                            <textarea class="form-control <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="excerpt" 
                                      name="excerpt" 
                                      rows="3"><?php echo e(old('excerpt')); ?></textarea>
                            <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Mô tả ngắn hiển thị trong danh sách bài viết</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội Dung <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="content" 
                                      name="content" 
                                      rows="15" 
                                      required><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            <input type="file" 
                                   class="form-control <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="featured_image" 
                                   name="featured_image" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   onchange="previewImage(this, 'featured-preview')">
                            <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="featured-preview" class="mt-2"></div>
                            <small class="text-muted">Kích thước tối đa: 5MB. Định dạng: JPG, PNG, GIF, WEBP</small>
                        </div>

                        <div class="mb-3">
                            <label for="post_images" class="form-label">Ảnh Bài Viết (Nhiều Ảnh)</label>
                            <input type="file" 
                                   class="form-control <?php $__errorArgs = ['post_images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="post_images" 
                                   name="post_images[]" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   multiple
                                   onchange="previewMultipleImages(this)">
                            <?php $__errorArgs = ['post_images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="images-preview" class="mt-2 row g-2"></div>
                            <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc. Mỗi ảnh tối đa 5MB. Định dạng: JPG, PNG, GIF, WEBP</small>
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">Thứ Tự Hiển Thị <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="<?php echo e(old('display_order', 0)); ?>" 
                                   min="0" 
                                   required>
                            <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_published" 
                                       name="is_published" 
                                       <?php echo e(old('is_published', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_published">
                                    Xuất Bản
                                </label>
                            </div>
                            <small class="text-muted">Bài viết sẽ hiển thị công khai</small>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Tạo Bài Viết
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Debug form submission
document.getElementById('postForm').addEventListener('submit', function(e) {
    console.log('Form is submitting...');
    console.log('Action:', this.action);
    console.log('Method:', this.method);
    
    // Check if all required fields are filled
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    
    if (!title || !content) {
        console.error('Missing required fields');
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin bắt buộc (Tiêu đề và Nội dung)');
        return false;
    }
    
    console.log('Form validation passed, submitting...');
});

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
                col.className = 'col-md-3';
                col.dataset.fileIndex = index;
                col.innerHTML = `
                    <div class="position-relative" style="margin-bottom: 10px;">
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
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
        
        // Store the DataTransfer object for later use
        input.files = dataTransfer.files;
    }
}

// Remove preview image
function removePreviewImage(index) {
    const input = document.getElementById('post_images');
    const preview = document.getElementById('images-preview');
    
    // Remove preview element
    const previewItem = preview.querySelector(`[data-file-index="${index}"]`);
    if (previewItem) {
        previewItem.remove();
    }
    
    // Remove file from input
    const dataTransfer = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dataTransfer.items.add(file);
        }
    });
    
    input.files = dataTransfer.files;
    
    // Re-render preview with updated indices
    preview.innerHTML = '';
    Array.from(input.files).forEach((file, newIndex) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-md-3';
            col.dataset.fileIndex = newIndex;
            col.innerHTML = `
                <div class="position-relative" style="margin-bottom: 10px;">
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/posts/create.blade.php ENDPATH**/ ?>