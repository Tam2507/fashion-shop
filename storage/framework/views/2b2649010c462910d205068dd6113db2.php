

<?php $__env->startSection('title', 'Chỉnh Sửa Banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Chỉnh Sửa Banner #<?php echo e($banner->id); ?></h1>
    <a href="<?php echo e(route('admin.banners.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.banners.update', $banner)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <input type="hidden" name="title" value="<?php echo e($banner->title); ?>">

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh Banner *</label>
                        <?php if($banner->image): ?>
                            <div class="mb-3">
                                <label class="form-label">Ảnh hiện tại:</label>
                                <div>
                                    <img src="/storage/<?php echo e($banner->image); ?>" alt="Banner" 
                                         class="img-thumbnail mb-2" style="max-width: 100%; max-height: 200px;">
                                </div>
                                <small class="text-muted d-block mb-2">Upload ảnh mới để thay thế</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image" name="image" accept="image/*">
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Chấp nhận: JPG, PNG, GIF. Tối đa 2MB. Kích thước đề xuất: 1920x400px</div>
                    </div>

                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL (khi click vào banner)</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="link_url" name="link_url" value="<?php echo e(old('link_url', $banner->link_url)); ?>" 
                               placeholder="/products hoặc https://example.com">
                        <?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Để trống sẽ chuyển đến trang sản phẩm mặc định</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="page" class="form-label">Hiển thị tại trang *</label>
                            <select class="form-select <?php $__errorArgs = ['page'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="page" name="page" required>
                                <option value="home" <?php echo e(old('page', $banner->page) == 'home' ? 'selected' : ''); ?>>Trang chủ</option>
                                <option value="products" <?php echo e(old('page', $banner->page) == 'products' ? 'selected' : ''); ?>>Trang sản phẩm</option>
                                <option value="all" <?php echo e(old('page', $banner->page) == 'all' ? 'selected' : ''); ?>>Tất cả trang</option>
                            </select>
                            <?php $__errorArgs = ['page'];
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
                        
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Thứ tự hiển thị *</label>
                            <input type="number" class="form-control <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="position" name="position" value="<?php echo e(old('position', $banner->position)); ?>" min="0" required>
                            <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước</div>
                        </div>
                    </div>

                    <input type="hidden" name="banner_type" value="<?php echo e($banner->banner_type); ?>">
                    <input type="hidden" name="is_active" value="on">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập Nhật Banner
                        </button>
                        <a href="<?php echo e(route('admin.banners.index')); ?>" class="btn btn-secondary">
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
                <h5><i class="fas fa-info-circle"></i> Thông tin</h5>
            </div>
            <div class="card-body">
                <p><strong>Vị trí:</strong> <?php echo e($banner->position); ?></p>
                <p><strong>Trang:</strong> <?php echo e($banner->page); ?></p>
                <p><strong>Loại:</strong> <?php echo e($banner->banner_type); ?></p>
                <p><strong>Trạng thái:</strong> 
                    <span class="badge bg-<?php echo e($banner->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($banner->is_active ? 'Đang hoạt động' : 'Tạm dừng'); ?>

                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_js'); ?>
<script>
// Simple file input - no complex JavaScript needed
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/banners/edit.blade.php ENDPATH**/ ?>