

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h1 class="mb-4">Đơn hàng của tôi</h1>
    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #<?php echo e($order->id); ?></h5>
                <span class="badge bg-<?php echo e($order->status_color); ?> fs-6"><?php echo e($order->status_label); ?></span>
            </div>
            <small class="text-muted">Ngày đặt: <?php echo e($order->created_at->format('d/m/Y H:i')); ?></small>
        </div>
        <div class="card-body">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                <div class="col-md-2 col-3">
                    <?php
                        $displayImage = $item->product->image ?? $item->product->images->first()->path ?? null;
                    ?>
                    <?php if($displayImage): ?>
                        <img src="/storage/<?php echo e($displayImage); ?>" 
                             alt="<?php echo e($item->product->name); ?>" 
                             class="img-fluid rounded"
                             style="width: 100%; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                            <i class="fas fa-box text-muted" style="font-size: 2rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 col-9">
                    <h6 class="mb-1"><?php echo e($item->product->name ?? 'N/A'); ?></h6>
                    <?php if($item->variant): ?>
                        <small class="text-muted">
                            <?php if($item->variant->size): ?>
                                Size: <?php echo e($item->variant->size); ?>

                            <?php endif; ?>
                            <?php if($item->variant->color): ?>
                                | Màu: <?php echo e($item->variant->color); ?>

                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                    <div class="mt-1">
                        <small>Số lượng: <?php echo e($item->quantity); ?></small>
                    </div>
                </div>
                <div class="col-md-4 col-12 text-md-end mt-2 mt-md-0">
                    <div class="text-muted small"><?php echo e(number_format($item->price, 0, ',', '.')); ?> VND</div>
                    <div class="fw-bold text-primary"><?php echo e(number_format($item->price * $item->quantity, 0, ',', '.')); ?> VND</div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-8">
                    <p class="mb-1"><strong>Địa chỉ giao hàng:</strong> <?php echo e($order->shipping_address); ?></p>
                    <p class="mb-0"><strong>SĐT:</strong> <?php echo e($order->phone); ?></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="mb-0">Tổng cộng: <span class="text-primary"><?php echo e(number_format($order->total_price, 0, ',', '.')); ?> VND</span></h5>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="alert alert-info">
        <p class="mb-0">Chưa có đơn hàng nào.</p>
    </div>
    <?php endif; ?>
    <div class="d-flex justify-content-center"><?php echo e($orders->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/orders/index.blade.php ENDPATH**/ ?>