

<?php $__env->startSection('title', 'Quản Lý Mã Giảm Giá'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản Lý Mã Giảm Giá</h2>
        <a href="<?php echo e(route('admin.coupons.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Mã Mới
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Loại</th>
                            <th>Giá Trị</th>
                            <th>Đơn Tối Thiểu</th>
                            <th>Giảm Tối Đa</th>
                            <th>Sử Dụng</th>
                            <th>Hiệu Lực</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong class="text-primary"><?php echo e($coupon->code); ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($coupon->type_label); ?></span>
                            </td>
                            <td>
                                <?php if($coupon->type === 'percentage'): ?>
                                    <?php echo e($coupon->value); ?>%
                                <?php elseif($coupon->type === 'fixed_amount'): ?>
                                    <?php echo e(number_format($coupon->value, 0, ',', '.')); ?>đ
                                <?php else: ?>
                                    Miễn phí ship
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($coupon->minimum_amount ? number_format($coupon->minimum_amount, 0, ',', '.') . 'đ' : '-'); ?>

                            </td>
                            <td>
                                <?php echo e($coupon->maximum_discount ? number_format($coupon->maximum_discount, 0, ',', '.') . 'đ' : '-'); ?>

                            </td>
                            <td>
                                <?php echo e($coupon->used_count); ?>

                                <?php if($coupon->usage_limit): ?>
                                    / <?php echo e($coupon->usage_limit); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <small>
                                    <?php echo e($coupon->starts_at->format('d/m/Y')); ?><br>
                                    đến <?php echo e($coupon->expires_at->format('d/m/Y')); ?>

                                </small>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="<?php echo e($coupon->id); ?>"
                                           <?php echo e($coupon->is_active ? 'checked' : ''); ?>>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('admin.coupons.edit', $coupon)); ?>" 
                                       class="btn btn-outline-primary" 
                                       title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-info send-notification" 
                                            data-id="<?php echo e($coupon->id); ?>"
                                            title="Gửi thông báo">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger delete-coupon" 
                                            data-id="<?php echo e($coupon->id); ?>"
                                            title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-<?php echo e($coupon->id); ?>" 
                                      action="<?php echo e(route('admin.coupons.destroy', $coupon)); ?>" 
                                      method="POST" 
                                      style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                                
                                <form id="notify-form-<?php echo e($coupon->id); ?>" 
                                      action="<?php echo e(route('admin.coupons.send-notification', $coupon)); ?>" 
                                      method="POST" 
                                      style="display: none;">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có mã giảm giá nào</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').change(function() {
        const couponId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/admin/coupons/${couponId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Có lỗi xảy ra');
                $(this).prop('checked', !isChecked);
            }
        });
    });
    
    // Send notification
    $('.send-notification').click(function() {
        const couponId = $(this).data('id');
        
        if (confirm('Bạn có chắc muốn gửi thông báo mã giảm giá này đến tất cả khách hàng?')) {
            $('#notify-form-' + couponId).submit();
        }
    });
    
    // Delete coupon
    $('.delete-coupon').click(function() {
        const couponId = $(this).data('id');
        
        if (confirm('Bạn có chắc muốn xóa mã giảm giá này?')) {
            $('#delete-form-' + couponId).submit();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/coupons/index.blade.php ENDPATH**/ ?>