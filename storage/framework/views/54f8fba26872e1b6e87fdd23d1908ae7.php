

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0"><i class="fas fa-tachometer-alt me-2"></i> Quản Lý Tổng Hợp</h1>
            <p class="text-muted">Quản lý toàn bộ cửa hàng từ một nơi</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('admin.admins.index')); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-user-cog me-2"></i>Giao Diện Admin Pro
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">
                <i class="fas fa-box me-2"></i>Sản Phẩm (<?php echo e($products->count()); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">
                <i class="fas fa-tags me-2"></i>Danh Mục (<?php echo e($categories->count()); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                <i class="fas fa-shopping-cart me-2"></i>Đơn Hàng (<?php echo e($orders->count()); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users me-2"></i>Khách Hàng (<?php echo e($users->count()); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button" role="tab">
                <i class="fas fa-user-shield me-2"></i>Admin (<?php echo e($admins->count()); ?>)
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="adminTabsContent">
        <!-- Products Tab -->
        <div class="tab-pane fade show active" id="products" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Quản Lý Sản Phẩm</h5>
                    <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($products->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f9f7f4;">
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" id="selectAllProducts" />
                                        </th>
                                        <th style="width: 80px;">Ảnh</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th style="width: 120px;">Danh Mục</th>
                                        <th style="width: 130px;">Giá</th>
                                        <th style="width: 100px;">Số Lượng</th>
                                        <th style="width: 100px;">Trạng Thái</th>
                                        <th style="width: 160px;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $products->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="product-checkbox" value="<?php echo e($product->id); ?>" />
                                        </td>
                                        <td>
                                            <div style="width: 60px; height: 60px; background: #f5f1e8; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                <?php if($product->image): ?>
                                                    <img src="/storage/<?php echo e($product->image); ?>" style="max-width: 100%; max-height: 100%; object-fit: cover;" alt="<?php echo e($product->name); ?>" />
                                                <?php else: ?>
                                                    <i class="fas fa-image text-muted"></i>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($product->name); ?></strong>
                                                <br><small class="text-muted"><?php echo e(Str::limit($product->description, 40)); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($product->category->name ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-primary"><?php echo e(number_format($product->price, 0, ',', '.')); ?>₫</strong>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($product->quantity > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                                <?php echo e($product->quantity); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($product->quantity > 0): ?>
                                                <span class="badge bg-success">Còn hàng</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Hết hàng</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="btn btn-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.products.destroy', $product->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($products->count() > 10): ?>
                            <div class="card-footer bg-light text-center">
                                <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-primary">
                                    Xem tất cả <?php echo e($products->count()); ?> sản phẩm
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 mb-2">Chưa có sản phẩm</h5>
                            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm Đầu Tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Categories Tab -->
        <div class="tab-pane fade" id="categories" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Quản Lý Danh Mục</h5>
                    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm Danh Mục
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($categories->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f9f7f4;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Danh Mục</th>
                                        <th>Mô Tả</th>
                                        <th>Số Sản Phẩm</th>
                                        <th>Ngày Tạo</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong>#<?php echo e($category->id); ?></strong></td>
                                        <td><strong><?php echo e($category->name); ?></strong></td>
                                        <td><?php echo e(Str::limit($category->description ?? 'N/A', 50)); ?></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($category->products->count()); ?></span>
                                        </td>
                                        <td><small class="text-muted"><?php echo e($category->created_at->format('d/m/Y')); ?></small></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.categories.edit', $category->id)); ?>" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.categories.destroy', $category->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-tags text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 mb-2">Chưa có danh mục</h5>
                            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Thêm Danh Mục Đầu Tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Orders Tab -->
        <div class="tab-pane fade" id="orders" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Quản Lý Đơn Hàng</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($orders->count()): ?>
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
                                    <?php $__currentLoopData = $orders->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                            <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($orders->count() > 10): ?>
                            <div class="card-footer bg-light text-center">
                                <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline-primary">
                                    Xem tất cả <?php echo e($orders->count()); ?> đơn hàng
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 mb-2">Chưa có đơn hàng</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Users Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Quản Lý Khách Hàng</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($users->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f9f7f4;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Số Đơn Hàng</th>
                                        <th>Ngày Đăng Ký</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong>#<?php echo e($user->id); ?></strong></td>
                                        <td><strong><?php echo e($user->name); ?></strong></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($user->orders->count()); ?></span>
                                        </td>
                                        <td><small class="text-muted"><?php echo e($user->created_at->format('d/m/Y')); ?></small></td>
                                        <td>
                                            <form action="<?php echo e(route('admin.users.delete', $user->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($users->count() > 10): ?>
                            <div class="card-footer bg-light text-center">
                                <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-primary">
                                    Xem tất cả <?php echo e($users->count()); ?> khách hàng
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 mb-2">Chưa có khách hàng</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Admins Tab -->
        <div class="tab-pane fade" id="admins" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #f5f1e8; border-bottom: 2px solid #8B3A3A;">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Quản Lý Admin</h5>
                    <a href="<?php echo e(route('admin.admins.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm Admin
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($admins->count()): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f9f7f4;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Ngày Tạo</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong>#<?php echo e($admin->id); ?></strong></td>
                                        <td>
                                            <strong><?php echo e($admin->name); ?></strong>
                                            <?php if($admin->id === auth()->id()): ?>
                                                <span class="badge bg-success ms-2">Bạn</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($admin->email); ?></td>
                                        <td><small class="text-muted"><?php echo e($admin->created_at->format('d/m/Y')); ?></small></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.admins.edit', $admin->id)); ?>" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($admin->id !== auth()->id()): ?>
                                                    <form action="<?php echo e(route('admin.admins.delete', $admin->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa admin này?');">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-shield text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 mb-2">Chưa có admin</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 600;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #8B3A3A;
    color: #8B3A3A;
    background: none;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #D4A574;
    color: #8B3A3A;
}

.table th {
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.4rem 0.7rem;
}
</style>

<script>
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllProducts');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

document.getElementById('selectAllProducts')?.addEventListener('change', toggleSelectAll);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/dashboard-unified.blade.php ENDPATH**/ ?>