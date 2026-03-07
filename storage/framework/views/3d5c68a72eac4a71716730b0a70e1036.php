

<?php $__env->startSection('content'); ?>
<h1>Chi tiết đơn hàng #<?php echo e($order->id); ?></h1>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white"><h5 class="mb-0">Sản phẩm</h5></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Sản phẩm</th><th>Giá</th><th>SL</th><th>Thành tiền</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($it->product->name ?? 'N/A'); ?></td>
                            <td><?php echo e(number_format($it->price, 0, ',', '.')); ?></td>
                            <td><?php echo e($it->quantity); ?></td>
                            <td class="fw-bold"><?php echo e(number_format($it->price * $it->quantity, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary text-white"><h5 class="mb-0">Thông tin</h5></div>
            <div class="card-body">
                <p><strong>Tổng:</strong> <span class="text-primary fw-bold"><?php echo e(number_format($order->total_price, 0, ',', '.')); ?> VND</span></p>
                <p><strong>Trạng thái:</strong> <span class="badge bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span></p>
                <p><strong>Địa chỉ:</strong> <?php echo e($order->shipping_address); ?></p>
                <p><strong>SĐT:</strong> <?php echo e($order->phone); ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
            </div>
        </div>
    </div>
</div>
<a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary mt-3">Quay lại</a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/orders/show.blade.php ENDPATH**/ ?>