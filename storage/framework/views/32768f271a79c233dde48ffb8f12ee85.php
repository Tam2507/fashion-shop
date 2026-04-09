

<?php $__env->startSection('title', 'Quản Lý Danh Mục'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tags"></i> Quản Lý Danh Mục</h1>
    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Danh Mục Mới
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($c->id); ?></td>
                        <td><strong><?php echo e($c->name); ?></strong></td>
                        <td><span class="text-muted"><?php echo e(Str::limit($c->description, 80) ?: '—'); ?></span></td>
                        <td>
                            <span class="badge bg-info"><?php echo e($c->products_count ?? $c->products()->count()); ?> sản phẩm</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.categories.edit', $c->id)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.categories.destroy', $c->id)); ?>" method="POST"
                                      class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-tags text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Chưa có danh mục nào</p>
                            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo Danh Mục Đầu Tiên
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($categories->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($categories->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>