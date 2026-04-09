

<?php $__env->startSection('title', 'Quản Lý Phương Thức Thanh Toán'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-credit-card"></i> Quản Lý Phương Thức Thanh Toán</h1>
    <a href="<?php echo e(route('admin.payment-methods.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Phương Thức
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
        <?php if($paymentMethods->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Tên</th>
                            <th>Mã</th>
                            <th>Vị trí</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php if($method->logo): ?>
                                    <img src="/storage/<?php echo e($method->logo); ?>" alt="<?php echo e($method->name); ?>" 
                                         style="height: 30px; max-width: 60px; object-fit: contain;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 30px; border-radius: 4px;">
                                        <i class="fas fa-credit-card text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($method->name); ?></strong>
                                <?php if($method->description): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($method->description, 50)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><code><?php echo e($method->code); ?></code></td>
                            <td><?php echo e($method->position); ?></td>
                            <td>
                                <button class="btn btn-sm btn-toggle <?php echo e($method->is_active ? 'btn-success' : 'btn-secondary'); ?>" 
                                        onclick="toggleStatus(<?php echo e($method->id); ?>, this)">
                                    <i class="fas fa-<?php echo e($method->is_active ? 'check' : 'times'); ?>"></i>
                                    <?php echo e($method->is_active ? 'Kích hoạt' : 'Tạm dừng'); ?>

                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('admin.payment-methods.edit', $method)); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('admin.payment-methods.destroy', $method)); ?>" 
                                          class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <?php echo e($paymentMethods->links()); ?>

        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-credit-card"></i>
                <p>Chưa có phương thức thanh toán nào</p>
                <a href="<?php echo e(route('admin.payment-methods.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Phương Thức Đầu Tiên
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_js'); ?>
<script>
function toggleStatus(id, button) {
    fetch(`/admin/payment-methods/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            const text = button.querySelector('i').nextSibling;
            
            if (data.is_active) {
                button.className = 'btn btn-sm btn-toggle btn-success';
                icon.className = 'fas fa-check';
                button.innerHTML = '<i class="fas fa-check"></i> Kích hoạt';
            } else {
                button.className = 'btn btn-sm btn-toggle btn-secondary';
                icon.className = 'fas fa-times';
                button.innerHTML = '<i class="fas fa-times"></i> Tạm dừng';
            }
            
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
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/payment-methods/index.blade.php ENDPATH**/ ?>