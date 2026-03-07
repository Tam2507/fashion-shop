

<?php $__env->startSection('title', 'Quản Lý Banner'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-images"></i> Quản Lý Banner</h1>
    <a href="<?php echo e(route('admin.banners.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Banner Mới
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Trang</th>
                        <th>Loại</th>
                        <th>Vị trí</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($banner->image): ?>
                                <img src="/storage/<?php echo e($banner->image); ?>" alt="<?php echo e($banner->title); ?>" 
                                     class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 50px; border-radius: 4px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($banner->title); ?></strong>
                            <?php if($banner->subtitle): ?>
                                <br><small class="text-muted"><?php echo e($banner->subtitle); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php switch($banner->page ?? 'all'):
                                case ('home'): ?>
                                    <span class="badge bg-success">Trang chủ</span>
                                    <?php break; ?>
                                <?php case ('products'): ?>
                                    <span class="badge bg-info">Sản phẩm</span>
                                    <?php break; ?>
                                <?php case ('all'): ?>
                                <?php default: ?>
                                    <span class="badge bg-secondary">Tất cả</span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                        <td>
                            <?php switch($banner->banner_type):
                                case ('hero'): ?>
                                    <span class="badge bg-primary">Hero</span>
                                    <?php break; ?>
                                <?php case ('promotion'): ?>
                                    <span class="badge bg-warning">Khuyến mãi</span>
                                    <?php break; ?>
                                <?php case ('announcement'): ?>
                                    <span class="badge bg-info">Thông báo</span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                        <td><?php echo e($banner->position); ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" 
                                       data-id="<?php echo e($banner->id); ?>" <?php echo e($banner->is_active ? 'checked' : ''); ?>>
                                <label class="form-check-label">
                                    <span class="status-text"><?php echo e($banner->is_active ? 'Hoạt động' : 'Tạm dừng'); ?></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.banners.show', $banner)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.banners.edit', $banner)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.banners.destroy', $banner)); ?>" 
                                      class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-images text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Chưa có banner nào</p>
                            <a href="<?php echo e(route('admin.banners.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo Banner Đầu Tiên
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($banners->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($banners->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const bannerId = this.dataset.id;
            const statusText = this.closest('td').querySelector('.status-text');
            
            fetch(`/admin/banners/${bannerId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusText.textContent = data.is_active ? 'Hoạt động' : 'Tạm dừng';
                    
                    // Show toast notification
                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">${data.message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    
                    toast.addEventListener('hidden.bs.toast', function() {
                        document.body.removeChild(toast);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; // Revert toggle
            });
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/banners/index.blade.php ENDPATH**/ ?>