

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-key"></i> Quên Mật Khẩu</h3>
            </div>
            <div class="card-body p-5">
                <p class="text-muted mb-4">Nhập email đã đăng ký để tiếp tục đặt lại mật khẩu.</p>

                <form method="POST" action="<?php echo e(route('password.email')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email"
                               class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('email')); ?>" required autofocus placeholder="Nhập email của bạn">
                        <?php $__errorArgs = ['email'];
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
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-arrow-right"></i> Tiếp Tục
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>