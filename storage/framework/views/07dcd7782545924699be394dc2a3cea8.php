

<?php $__env->startSection('title', 'Tạo Mã Giảm Giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="mb-4">
        <h2>Tạo Mã Giảm Giá Mới</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.coupons.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
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
                                   value="<?php echo e(old('code')); ?>"
                                   placeholder="VD: SUMMER2024"
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
                            <small class="text-muted">Mã sẽ tự động chuyển thành chữ in hoa</small>
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
                                    <option value="percentage" <?php echo e(old('type', 'percentage') === 'percentage' ? 'selected' : ''); ?>>Giảm theo %</option>
                                    <option value="free_shipping" <?php echo e(old('type') === 'free_shipping' ? 'selected' : ''); ?>>Miễn phí vận chuyển</option>
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
                                       value="<?php echo e(old('value')); ?>"
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
                                <small class="text-muted" id="value-hint">Nhập % hoặc số tiền</small>
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
                                       value="<?php echo e(old('minimum_amount')); ?>"
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
                                <small class="text-muted">Để trống nếu không giới hạn</small>
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
                                       value="<?php echo e(old('maximum_discount')); ?>"
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
                                <small class="text-muted">Chỉ áp dụng cho giảm theo %</small>
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
                                   value="<?php echo e(old('usage_limit')); ?>"
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
                            <small class="text-muted">Để trống nếu không giới hạn</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control flatpickr <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="starts_at" 
                                       name="starts_at" 
                                       value="<?php echo e(old('starts_at', now()->format('d/m/Y H:i'))); ?>"
                                       placeholder="dd/mm/yyyy HH:MM"
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
                                <input type="text" 
                                       class="form-control flatpickr <?php $__errorArgs = ['expires_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="expires_at" 
                                       name="expires_at" 
                                       value="<?php echo e(old('expires_at', now()->addDays(30)->format('d/m/Y H:i'))); ?>"
                                       placeholder="dd/mm/yyyy HH:MM"
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
                                       <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt ngay
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="send_notification" 
                                       name="send_notification" 
                                       value="1"
                                       <?php echo e(old('send_notification') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="send_notification">
                                    <strong>Gửi thông báo đến tất cả khách hàng</strong>
                                </label>
                                <small class="d-block text-muted">Email sẽ được gửi đến tất cả khách hàng đã đăng ký</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo Mã Giảm Giá
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
                    <h5 class="mb-0">Hướng Dẫn</h5>
                </div>
                <div class="card-body">
                    <h6>Loại giảm giá:</h6>
                    <ul>
                        <li><strong>Giảm theo %:</strong> Giảm theo phần trăm giá trị đơn hàng</li>
                        <li><strong>Miễn phí ship:</strong> Miễn phí vận chuyển</li>
                    </ul>

                    <h6 class="mt-3">Lưu ý:</h6>
                    <ul>
                        <li>Mã giảm giá phải là duy nhất</li>
                        <li>Giá trị giảm tối đa chỉ áp dụng cho giảm theo %</li>
                        <li>Có thể giới hạn số lần sử dụng</li>
                        <li>Gửi thông báo sẽ email đến tất cả khách hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr('.flatpickr', {
    enableTime: true,
    dateFormat: 'd/m/Y H:i',
    time_24hr: true,
    locale: { firstDayOfWeek: 1 }
});

$(document).ready(function() {
    $('#type').change(function() {
        const type = $(this).val();
        const valueHint = $('#value-hint');
        if (type === 'percentage') {
            valueHint.text('Nhập % (VD: 10 = giảm 10%)');
            $('#maximum_discount').prop('disabled', false);
        } else {
            valueHint.text('Nhập 0 cho miễn phí ship');
            $('#maximum_discount').prop('disabled', true);
        }
    });
    $('#type').trigger('change');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/coupons/create.blade.php ENDPATH**/ ?>