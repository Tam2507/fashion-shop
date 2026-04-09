

<?php $__env->startSection('title', 'Chi Tiết Liên Hệ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="<?php echo e(route('admin.contacts.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Contact Details -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Thông Tin Liên Hệ</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-user"></i> Người gửi:</strong><br>
                            <?php echo e($contact->name); ?>

                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-envelope"></i> Email:</strong><br>
                            <a href="mailto:<?php echo e($contact->email); ?>"><?php echo e($contact->email); ?></a>
                        </div>
                    </div>

                    <?php if($contact->phone): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-phone"></i> Số điện thoại:</strong><br>
                            <a href="tel:<?php echo e($contact->phone); ?>"><?php echo e($contact->phone); ?></a>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-clock"></i> Thời gian:</strong><br>
                            <?php echo e($contact->created_at->format('H:i d/m/Y')); ?>

                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong><i class="fas fa-clock"></i> Thời gian:</strong><br>
                            <?php echo e($contact->created_at->format('H:i d/m/Y')); ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <strong><i class="fas fa-tag"></i> Chủ đề:</strong><br>
                        <?php echo e($contact->subject); ?>

                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-comment"></i> Nội dung:</strong>
                        <div class="p-3 bg-light rounded mt-2">
                            <?php echo e($contact->message); ?>

                        </div>
                    </div>

                    <?php if($contact->admin_reply): ?>
                    <div class="alert alert-success">
                        <strong><i class="fas fa-reply"></i> Đã trả lời:</strong>
                        <div class="mt-2"><?php echo e($contact->admin_reply); ?></div>
                        <small class="text-muted">
                            Bởi <?php echo e($contact->repliedBy->name ?? 'Admin'); ?> 
                            vào <?php echo e($contact->replied_at->format('H:i d/m/Y')); ?>

                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reply Form -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-reply"></i> Trả Lời</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.contacts.reply', $contact->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Nội dung trả lời</label>
                            <textarea name="admin_reply" class="form-control <?php $__errorArgs = ['admin_reply'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" required><?php echo e(old('admin_reply', $contact->admin_reply)); ?></textarea>
                            <?php $__errorArgs = ['admin_reply'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Nội dung này sẽ được gửi qua email đến: <strong><?php echo e($contact->email); ?></strong>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> <?php echo e($contact->admin_reply ? 'Cập nhật trả lời' : 'Gửi trả lời'); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/contacts/show.blade.php ENDPATH**/ ?>