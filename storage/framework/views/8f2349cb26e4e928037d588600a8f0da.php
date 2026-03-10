

<?php $__env->startSection('title', 'Chi Tiết Đơn Hàng #' . $order->id); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-invoice"></i> Chi Tiết Đơn Hàng #<?php echo e($order->id); ?></h1>
    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-user"></i> Thông Tin Khách Hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tên khách hàng:</strong> <?php echo e($order->user->name ?? 'Khách'); ?></p>
                        <p><strong>Email:</strong> <?php echo e($order->user->email ?? $order->guest_email ?? '-'); ?></p>
                        <p><strong>Số điện thoại:</strong> <?php echo e($order->phone ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Địa chỉ giao hàng:</strong></p>
                        <p class="text-muted">
                            <?php if(is_array($order->shipping_address)): ?>
                                <?php echo e($order->shipping_address['address'] ?? ''); ?><br>
                                <?php echo e($order->shipping_address['city'] ?? ''); ?>, <?php echo e($order->shipping_address['district'] ?? ''); ?>

                            <?php else: ?>
                                <?php echo e($order->shipping_address); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php if($order->notes): ?>
                <div class="mt-3">
                    <p><strong>Ghi chú:</strong></p>
                    <p class="text-muted"><?php echo e($order->notes); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-box"></i> Sản Phẩm Đã Đặt</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Biến thể</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($item->product->name ?? 'Sản phẩm đã xóa'); ?></strong>
                                </td>
                                <td>
                                    <?php if($item->variant): ?>
                                        <small class="text-muted">
                                            <?php echo e($item->variant->size ?? ''); ?> 
                                            <?php if($item->variant->color): ?>
                                                - <?php echo e($item->variant->color); ?>

                                            <?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?php echo e(number_format($item->price, 0, ',', '.')); ?>đ</td>
                                <td class="text-center"><?php echo e($item->quantity); ?></td>
                                <td class="text-end"><strong><?php echo e(number_format($item->price * $item->quantity, 0, ',', '.')); ?>đ</strong></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tạm tính:</strong></td>
                                <td class="text-end"><?php echo e(number_format($order->total_price, 0, ',', '.')); ?>đ</td>
                            </tr>
                            <?php if($order->discount_amount > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end">
                                    <strong>Giảm giá:</strong>
                                    <?php if($order->coupon_code): ?>
                                        <small class="text-muted">(<?php echo e($order->coupon_code); ?>)</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end text-danger">-<?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <?php if($order->shipping_cost > 0): ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                <td class="text-end"><?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?>đ</td>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-active">
                                <td colspan="4" class="text-end"><h5>Tổng cộng:</h5></td>
                                <td class="text-end"><h5 class="text-primary"><?php echo e(number_format($order->final_total, 0, ',', '.')); ?>đ</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status & Actions -->
    <div class="col-lg-4">
        <!-- Status Management -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-tasks"></i> Quản Lý Trạng Thái</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Trạng thái hiện tại:</label>
                    <div>
                        <span class="badge bg-<?php echo e($order->status_color); ?> fs-6">
                            <?php echo e($order->status_label); ?>

                        </span>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('admin.orders.update-status', $order->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label for="status" class="form-label">Cập nhật trạng thái:</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="received" <?php echo e($order->status == 'received' ? 'selected' : ''); ?>>Đã nhận</option>
                            <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>Đang xử lý</option>
                            <option value="confirmed" <?php echo e($order->status == 'confirmed' ? 'selected' : ''); ?>>Đã xác nhận</option>
                            <option value="shipped" <?php echo e($order->status == 'shipped' ? 'selected' : ''); ?>>Đã gửi hàng</option>
                            <option value="delivered" <?php echo e($order->status == 'delivered' ? 'selected' : ''); ?>>Đã giao hàng</option>
                            <option value="cancelled" <?php echo e($order->status == 'cancelled' ? 'selected' : ''); ?>>Đã hủy</option>
                            <option value="refunded" <?php echo e($order->status == 'refunded' ? 'selected' : ''); ?>>Đã hoàn tiền</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Cập nhật trạng thái
                    </button>
                </form>

                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Lưu ý:</strong> Chỉ đơn hàng có trạng thái "Đã giao hàng" mới được tính vào doanh thu.
                    </small>
                </div>
            </div>
        </div>

        <!-- Shipping Label -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-print"></i> In Phiếu Giao Hàng</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.orders.print-shipping', $order->id)); ?>" target="_blank">
                    <div class="mb-3">
                        <label for="shipping_provider" class="form-label">Chọn đơn vị vận chuyển:</label>
                        <select name="shipping_provider" id="shipping_provider" class="form-select">
                            <option value="ghn">Giao Hàng Nhanh (GHN)</option>
                            <option value="jnt">J&T Express</option>
                            <option value="shopee">Shopee Express</option>
                            <option value="viettel">Viettel Post</option>
                            <option value="vnpost">VNPost</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-print"></i> In phiếu giao hàng
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Thông Tin Đơn Hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Mã đơn hàng:</strong> #<?php echo e($order->id); ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
                <p><strong>Cập nhật lần cuối:</strong> <?php echo e($order->updated_at->format('d/m/Y H:i')); ?></p>
            </div>
        </div>

        <!-- Delete Order -->
        <?php if(in_array($order->status, ['cancelled', 'refunded'])): ?>
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5><i class="fas fa-trash"></i> Xóa Đơn Hàng</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Đơn hàng đã hủy có thể được xóa khỏi hệ thống.</p>
                <form method="POST" action="<?php echo e(route('admin.orders.destroy', $order->id)); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Xóa đơn hàng
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>