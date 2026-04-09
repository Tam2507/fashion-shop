

<?php $__env->startSection('title', 'Sửa Mã Giảm Giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="mb-4">
        <h2>Sửa Mã Giảm Giá: <?php echo e($coupon->code); ?></h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.coupons.update', $coupon)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Mã Giảm Giá <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="code" 
                                   name="code" 
                                   value="<?php echo e(old('code', $coupon->code)); ?>"
                                   required>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Loại Giảm Giá <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="percentage" <?php echo e(old('type', $coupon->type) === 'percentage' ? 'selected' : ''); ?>>Giảm theo %</option>
                                    <option value="fixed_amount" <?php echo e(old('type', $coupon->type) === 'fixed_amount' ? 'selected' : ''); ?>>Giảm cố định</option>
                                    <option value="free_shipping" <?php echo e(old('type', $coupon->type) === 'free_shipping' ? 'selected' : ''); ?>>Miễn phí vận chuyển</option>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Giá Trị <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="value" 
                                       name="value" 
                                       value="<?php echo e(old('value', $coupon->value)); ?>"
                                       step="0.01"
                                       min="0"
                                       required>
                                <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="minimum_amount" class="form-label">Đơn Hàng Tối Thiểu</label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['minimum_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="minimum_amount" 
                                       name="minimum_amount" 
                                       value="<?php echo e(old('minimum_amount', $coupon->minimum_amount)); ?>"
                                       step="1000"
                                       min="0">
                                <?php $__errorArgs = ['minimum_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="maximum_discount" class="form-label">Giảm Tối Đa</label>
                                <input type="number" 
                                       class="form-control <?php $__errorArgs = ['maximum_discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="maximum_discount" 
                                       name="maximum_discount" 
                                       value="<?php echo e(old('maximum_discount', $coupon->maximum_discount)); ?>"
                                       step="1000"
                                       min="0">
                                <?php $__errorArgs = ['maximum_discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Giới Hạn Số Lần Sử Dụng</label>
                            <input type="number" 
                                   class="form-control <?php $__errorArgs = ['usage_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="usage_limit" 
                                   name="usage_limit" 
                                   value="<?php echo e(old('usage_limit', $coupon->usage_limit)); ?>"
                                   min="1">
                            <?php $__errorArgs = ['usage_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Đã sử dụng: <?php echo e($coupon->used_count); ?> lần</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="starts_at" 
                                       name="starts_at" 
                                       value="<?php echo e(old('starts_at', $coupon->starts_at->format('Y-m-d\TH:i'))); ?>"
                                       required>
                                <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expires_at" class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control <?php $__errorArgs = ['expires_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="expires_at" 
                                       name="expires_at" 
                                       value="<?php echo e(old('expires_at', $coupon->expires_at->format('Y-m-d\TH:i'))); ?>"
                                       required>
                                <?php $__errorArgs = ['expires_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       <?php echo e(old('is_active', $coupon->is_active) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập Nhật
                            </button>
                            <a href="<?php echo e(route('admin.coupons.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống Kê</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Đã sử dụng:</strong> <?php echo e($coupon->used_count); ?> lần
                    </div>
                    <div class="mb-3">
                        <strong>Trạng thái:</strong> 
                        <span class="badge bg-<?php echo e($coupon->is_active ? 'success' : 'secondary'); ?>">
                            <?php echo e($coupon->is_active ? 'Đang hoạt động' : 'Tạm dừng'); ?>

                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Còn hiệu lực:</strong> 
                        <?php if($coupon->expires_at > now()): ?>
                            <span class="text-success"><?php echo e($coupon->expires_at->diffForHumans()); ?></span>
                        <?php else: ?>
                            <span class="text-danger">Đã hết hạn</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/coupons/edit.blade.php ENDPATH**/ ?>