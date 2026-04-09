

<?php $__env->startSection('title', 'Quản Lý Tài Khoản Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-shield"></i> Quản Lý Tài Khoản Admin</h1>
    <a href="/admin/admins/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Admin
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

<!-- Search -->
<form method="GET" action="<?php echo e(request()->url()); ?>" class="mb-4">
    <div class="input-group" style="max-width: 420px;">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
               class="form-control" placeholder="Tìm theo tên, email...">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(request()->url()); ?>" class="btn btn-outline-secondary">Xóa</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Avatar</th>
                        <th>Tên Admin</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($admin->avatar): ?>
                                <img src="/storage/<?php echo e($admin->avatar); ?>" alt="<?php echo e($admin->name); ?>"
                                     class="rounded-circle" style="width:48px;height:48px;object-fit:cover;border:2px solid #dee2e6;">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:48px;height:48px;background:#8B3A3A;">
                                    <i class="fas fa-user-shield text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($admin->name); ?></strong>
                            <?php if($admin->id === auth()->id()): ?>
                                <span class="badge bg-warning text-dark ms-1">Bạn</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($admin->email); ?></td>
                        <td><span class="badge bg-success"><i class="fas fa-check-circle"></i> Hoạt động</span></td>
                        <td><small class="text-muted"><?php echo e($admin->created_at->format('d/m/Y')); ?></small></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/admin/admins/<?php echo e($admin->id); ?>/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if($admins->count() > 1 && $admin->id !== auth()->id()): ?>
                                    <form method="POST" action="/admin/admins/<?php echo e($admin->id); ?>"
                                          class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa admin này?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-user-shield text-muted" style="font-size:3rem;"></i>
                            <p class="text-muted mt-2">Chưa có tài khoản admin nào</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($admins->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($admins->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/admins/index.blade.php ENDPATH**/ ?>