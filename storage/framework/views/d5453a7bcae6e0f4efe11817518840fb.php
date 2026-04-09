<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="<?php echo e(route('admin.products.update', $product->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Tên Sản Phẩm -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" value="<?php echo e($product->name); ?>" required>
                    </div>
                    
                    <!-- Danh Mục & Giá -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh Mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select form-select-lg" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e($product->category_id == $c->id ? 'selected' : ''); ?>>
                                    <?php echo e($c->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá (VND) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control form-control-lg" value="<?php echo e($product->price); ?>" min="0" step="1000" required>
                        </div>
                    </div>
                    
                    <!-- Số Lượng -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Số Lượng Tồn Kho <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control form-control-lg" value="<?php echo e($product->quantity); ?>" min="0" required>
                    </div>
                    
                    <!-- Mô Tả -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô Tả Sản Phẩm</label>
                        <textarea name="description" class="form-control" rows="5"><?php echo e($product->description); ?></textarea>
                    </div>
                    
                    <!-- Trạng Thái -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo e($product->is_active ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold" for="is_active">
                                Kích hoạt sản phẩm
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Ảnh Chính -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ảnh Chính</label>
                        <?php if($product->image): ?>
                        <div class="mb-3">
                            <img src="/storage/<?php echo e($product->image); ?>" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Bỏ qua nếu không thay đổi</small>
                    </div>
                    
                    <!-- SEO -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="fas fa-search me-2"></i>SEO</h6>
                            <div class="mb-3">
                                <label class="form-label small">SEO Title</label>
                                <input type="text" name="seo_title" class="form-control form-control-sm" value="<?php echo e($product->seo_title); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">SEO Description</label>
                                <textarea name="seo_description" class="form-control form-control-sm" rows="3"><?php echo e($product->seo_description); ?></textarea>
                            </div>
                            <div>
                                <label class="form-label small">Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control form-control-sm" value="<?php echo e($product->meta_keywords); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                </button>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-lg">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
<?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/products/partials/basic-info.blade.php ENDPATH**/ ?>