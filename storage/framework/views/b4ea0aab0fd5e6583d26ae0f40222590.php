

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-lock"></i> Đặt Lại Mật Khẩu</h3>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Đặt mật khẩu mới cho tài khoản <strong><?php echo e($email); ?></strong></p>

                <form method="POST" action="<?php echo e(route('password.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật Khẩu Mới</label>
                        <input type="password" name="password"
                               class="form-control form-control-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required placeholder="Nhập mật khẩu mới (ít nhất 8 ký tự)">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Xác Nhận Mật Khẩu</label>
                        <input type="password" name="password_confirmation"
                               class="form-control form-control-lg"
                               required placeholder="Nhập lại mật khẩu mới">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-save"></i> Lưu Mật Khẩu Mới
                    </button>
                </form>
                <hr>
                <p class="text-center text-muted">
                    <a href="<?php echo e(route('login')); ?>" class="text-primary fw-bold">
                        <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>