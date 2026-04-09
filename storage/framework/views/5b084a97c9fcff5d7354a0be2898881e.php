

<?php $__env->startSection('title', 'Quản Lý Tài Khoản'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> Quản Lý Tài Khoản</h1>
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
               class="form-control" placeholder="Tìm theo tên, email, số điện thoại...">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(request()->url()); ?>" class="btn btn-outline-secondary">Xóa</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px;">STT</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th style="width:80px;">Ảnh</th>
                        <th>Ngày tham gia</th>
                        <th>Phân quyền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted"><?php echo e($loop->iteration + ($users->currentPage() - 1) * $users->perPage()); ?></td>
                        <td><strong><?php echo e($u->name); ?></strong>
                            <?php if($u->id === auth()->id()): ?>
                                <span class="badge bg-warning text-dark ms-1">Bạn</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($u->email); ?></td>
                        <td>
                            <?php if($u->avatar): ?>
                                <img src="/storage/<?php echo e($u->avatar); ?>" alt="<?php echo e($u->name); ?>"
                                     class="rounded-circle" style="width:44px;height:44px;object-fit:cover;border:2px solid #dee2e6;">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width:44px;height:44px;background:<?php echo e($u->is_admin ? '#8B3A3A' : '#6c757d'); ?>;">
                                    <i class="fas fa-<?php echo e($u->is_admin ? 'user-shield' : 'user'); ?> text-white"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?php echo e($u->created_at->diffForHumans()); ?></small></td>
                        <td>
                            <?php if($u->is_admin): ?>
                                <span class="badge" style="background:#8B3A3A;">admin</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">user</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-success">active</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <?php if($u->is_admin): ?>
                                    <a href="/admin/admins/<?php echo e($u->id); ?>/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($u->id !== auth()->id()): ?>
                                        <form method="POST" action="/admin/admins/<?php echo e($u->id); ?>"
                                              class="d-inline" onsubmit="return confirm('Xác nhận xóa admin này?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <form action="<?php echo e(route('admin.users.delete', $u->id)); ?>" method="POST"
                                          class="d-inline" onsubmit="return confirm('Xác nhận xóa người dùng này?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i> Chưa có tài khoản nào
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
            <div class="d-flex justify-content-center p-3"><?php echo e($users->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/users/index.blade.php ENDPATH**/ ?>