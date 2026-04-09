

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h2 mb-0"><i class="fas fa-chart-line me-2"></i> Thống Kê</h1>
            <p class="text-muted">Tổng quan về cửa hàng</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #8B3A3A 0%, #A85252 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-shopping-cart fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted small mb-0">Tổng Đơn Hàng</p>
                            <h3 class="mb-0"><?php echo e($totalOrders ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #D4A574 0%, #E8C89C 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-money-bill-wave fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted small mb-0">Doanh Thu</p>
                            <h3 class="mb-0"><?php echo e(number_format($totalRevenue ?? 0, 0, ',', '.')); ?>₫</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #6c757d 0%, #8a92a4 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-box fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted small mb-0">Sản Phẩm</p>
                            <h3 class="mb-0"><?php echo e($totalProducts ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #28a745 0%, #5cc97a 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-users fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted small mb-0">Người Dùng</p>
                            <h3 class="mb-0"><?php echo e($totalUsers ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Management -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i> Quản Lý Nhanh</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('admin.products.index')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-box me-2 text-primary"></i> 
                                <strong>Sản Phẩm</strong>
                                <br><small class="text-muted">Thêm, sửa, xóa sản phẩm</small>
                            </div>
                            <span class="badge bg-primary"><?php echo e($totalProducts ?? 0); ?></span>
                        </a>
                        <a href="<?php echo e(route('admin.categories.index')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tags me-2 text-info"></i> 
                                <strong>Danh Mục</strong>
                                <br><small class="text-muted">Quản lý danh mục sản phẩm</small>
                            </div>
                            <span class="badge bg-info"><?php echo e($totalCategories ?? 0); ?></span>
                        </a>
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shopping-cart me-2 text-success"></i> 
                                <strong>Đơn Hàng</strong>
                                <br><small class="text-muted">Xem và xử lý đơn hàng</small>
                            </div>
                            <span class="badge bg-success"><?php echo e($totalOrders ?? 0); ?></span>
                        </a>
                        <a href="<?php echo e(route('admin.users')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-users me-2 text-warning"></i> 
                                <strong>Người Dùng</strong>
                                <br><small class="text-muted">Quản lý tài khoản khách hàng</small>
                            </div>
                            <span class="badge bg-warning"><?php echo e($totalUsers ?? 0); ?></span>
                        </a>
                        <a href="<?php echo e(route('admin.admins.index')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-shield me-2 text-danger"></i> 
                                <strong>Quản Trị Viên</strong>
                                <br><small class="text-muted">Quản lý admin</small>
                            </div>
                            <span class="badge bg-danger">Admin</span>
                        </a>
                        <a href="<?php echo e(route('admin.unified')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tachometer-alt me-2 text-success"></i> 
                                <strong>Quản Lý Tổng Hợp</strong>
                                <br><small class="text-muted">Quản lý tất cả từ một nơi</small>
                            </div>
                            <span class="badge bg-success">New</span>
                        </a>
                        <a href="<?php echo e(route('admin.admins.index')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-top-2" style="border-top: 2px solid #8B3A3A !important;">
                            <div>
                                <i class="fas fa-user-cog me-2 text-primary"></i> 
                                <strong>Giao Diện Admin</strong>
                                <br><small class="text-muted">Quản lý với sidebar chuyên nghiệp</small>
                            </div>
                            <span class="badge bg-primary">Pro</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Đơn Hàng Gần Đây</h5>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <?php if($recentOrders->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f9f7f4;">
                                    <tr>
                                        <th>Mã ĐH</th>
                                        <th>Khách Hàng</th>
                                        <th>Tổng Tiền</th>
                                        <th>Trạng Thái</th>
                                        <th>Ngày Tạo</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong class="text-primary">#<?php echo e($order->id); ?></strong></td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($order->user->name ?? 'N/A'); ?></strong>
                                                <br><small class="text-muted"><?php echo e($order->user->email ?? ''); ?></small>
                                            </div>
                                        </td>
                                        <td><strong class="text-success"><?php echo e(number_format($order->total_price, 0, ',', '.')); ?>₫</strong></td>
                                        <td>
                                            <span class="badge bg-warning">Đang xử lý</span>
                                        </td>
                                        <td><small class="text-muted"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></small></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">Không có đơn hàng nào</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mt-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Hoạt Động Hôm Nay</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Đơn hàng mới:</span>
                                <span class="badge bg-success"><?php echo e($todayOrders ?? 0); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Khách hàng mới:</span>
                                <span class="badge bg-info"><?php echo e($todayUsers ?? 0); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Doanh thu hôm nay:</span>
                                <span class="badge bg-primary"><?php echo e(number_format($todayRevenue ?? 0, 0, ',', '.')); ?>₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.card-header h5 {
    color: #8B3A3A;
    font-weight: 600;
}

.table th {
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.table td {
    vertical-align: middle;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>