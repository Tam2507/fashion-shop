

<?php $__env->startSection('title', 'Tạo Banner Mới'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus"></i> Tạo Banner Mới</h1>
    <a href="<?php echo e(route('admin.banners.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.banners.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
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

                    <input type="hidden" name="title" value="Banner <?php echo e(date('Y-m-d H:i:s')); ?>">

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh Banner *</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required>
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
                        <div class="form-text">Chấp nhận: JPG, PNG, WEBP. Tối đa 2MB. Kích thước đề xuất: 1920x400px</div>
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
                               id="link_url" name="link_url" value="<?php echo e(old('link_url')); ?>" 
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
                                <option value="home" <?php echo e(old('page') == 'home' ? 'selected' : ''); ?>>Trang chủ</option>
                                <option value="products" <?php echo e(old('page') == 'products' ? 'selected' : ''); ?>>Trang sản phẩm</option>
                                <option value="all" <?php echo e(old('page', 'home') == 'all' ? 'selected' : ''); ?>>Tất cả trang</option>
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
                                   id="position" name="position" value="<?php echo e(old('position', 0)); ?>" min="0" required>
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

                    <input type="hidden" name="banner_type" value="hero">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Tạo Banner
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
                <h5><i class="fas fa-info-circle"></i> Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <p><strong>Trang hiển thị:</strong></p>
                <ul>
                    <li><strong>Trang chủ:</strong> Banner chỉ hiển thị ở trang chủ</li>
                    <li><strong>Trang sản phẩm:</strong> Banner chỉ hiển thị ở trang sản phẩm</li>
                    <li><strong>Tất cả trang:</strong> Banner hiển thị ở mọi trang</li>
                </ul>
                <p class="mt-3"><strong>Thứ tự hiển thị:</strong></p>
                <p>Banner có số thứ tự nhỏ hơn sẽ hiển thị trước. Ví dụ: Banner có position = 1 sẽ hiển thị trước banner có position = 2.</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/banners/create.blade.php ENDPATH**/ ?>