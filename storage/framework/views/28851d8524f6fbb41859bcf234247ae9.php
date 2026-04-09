

<?php $__env->startSection('title', 'Quản Lý Liên Hệ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-envelope"></i> Quản Lý Liên Hệ</h2>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <?php if($contacts->isEmpty()): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có liên hệ nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">Trạng thái</th>
                                <th>Người gửi</th>
                                <th>Chủ đề</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e(!$contact->is_read ? 'table-primary' : ''); ?>">
                                <td>
                                    <?php if(!$contact->is_read): ?>
                                        <span class="badge bg-primary">Mới</span>
                                    <?php elseif($contact->admin_reply): ?>
                                        <span class="badge bg-success">Đã trả lời</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã đọc</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($contact->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($contact->email); ?></small>
                                    <?php if($contact->phone): ?>
                                        <br><small class="text-muted"><i class="fas fa-phone"></i> <?php echo e($contact->phone); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($contact->subject); ?></td>
                                <td><?php echo e(Str::limit($contact->message, 50)); ?></td>
                                <td>
                                    <?php echo e($contact->created_at->format('H:i d/m/Y')); ?><br>
                                    <small class="text-muted"><?php echo e($contact->created_at->diffForHumans()); ?></small>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.contacts.show', $contact->id)); ?>" class="btn btn-sm btn-primary mb-1">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <form action="<?php echo e(route('admin.contacts.destroy', $contact->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger mb-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($contacts->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/contacts/index.blade.php ENDPATH**/ ?>