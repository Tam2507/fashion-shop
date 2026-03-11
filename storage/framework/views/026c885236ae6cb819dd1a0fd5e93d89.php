

<?php $__env->startSection('title', 'Quản Lý Tính Năng'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-star"></i> Quản Lý Tính Năng</h1>
    <a href="<?php echo e(route('admin.features.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Tính Năng Mới
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
                        <th>Icon</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Vị trí</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="feature-icon-preview" 
                                 style="width: 50px; height: 50px; background-color: <?php echo e($feature->background_color); ?>; color: <?php echo e($feature->icon_color); ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="<?php echo e($feature->icon); ?>" style="font-size: 20px;"></i>
                            </div>
                        </td>
                        <td>
                            <strong><?php echo e($feature->title); ?></strong>
                        </td>
                        <td><?php echo e(Str::limit($feature->description, 50)); ?></td>
                        <td><?php echo e($feature->position); ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" 
                                       data-id="<?php echo e($feature->id); ?>" <?php echo e($feature->is_active ? 'checked' : ''); ?>>
                                <label class="form-check-label">
                                    <span class="status-text"><?php echo e($feature->is_active ? 'Hoạt động' : 'Tạm dừng'); ?></span>
                                </label>
                            </div>
                        </td>
                        <td><?php echo e($feature->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.features.show', $feature)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.features.edit', $feature)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.features.destroy', $feature)); ?>" 
                                      class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tính năng này?')">
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
                            <i class="fas fa-star text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Chưa có tính năng nào</p>
                            <a href="<?php echo e(route('admin.features.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo Tính Năng Đầu Tiên
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($features->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($features->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const featureId = this.dataset.id;
            const statusText = this.closest('td').querySelector('.status-text');
            
            fetch(`/admin/features/${featureId}/toggle-status`, {
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/features/index.blade.php ENDPATH**/ ?>