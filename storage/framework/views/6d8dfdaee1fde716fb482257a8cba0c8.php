

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0"><i class="fas fa-sign-in-alt"></i> Đăng Nhập</h3>
            </div>
            <div class="card-body p-5">
                <?php if(session('status')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('login')); ?>"><?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control form-control-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ tôi</label>
                        </div>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-primary small">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-lock-open"></i> Đăng Nhập
                    </button>
                </form>
                <hr>
                <p class="text-center text-muted">Chưa có tài khoản? <a href="<?php echo e(route('register')); ?>" class="text-primary fw-bold">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/auth/login.blade.php ENDPATH**/ ?>