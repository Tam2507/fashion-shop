

<?php $__env->startSection('title', 'Quản Lý Section Sản Phẩm'); ?>
<?php $__env->startSection('page_title', 'Quản Lý Section Sản Phẩm'); ?>
<?php $__env->startSection('header_icon', 'fas fa-layer-group'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-layer-group"></i> Danh Sách Section</h5>
            <a href="<?php echo e(route('admin.product-sections.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Section Mới
            </a>
        </div>
        <div class="card-body">
            <?php if($sections->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">STT</th>
                                <th>Tên Section</th>
                                <th>Slug</th>
                                <th>Số Sản Phẩm</th>
                                <th>Tối Đa</th>
                                <th>Thứ Tự</th>
                                <th>Trạng Thái</th>
                                <th width="200">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <strong><?php echo e($section->name); ?></strong>
                                        <?php if($section->description): ?>
                                            <br><small class="text-muted"><?php echo e(Str::limit($section->description, 50)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><code><?php echo e($section->slug); ?></code></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($section->products_count); ?> sản phẩm</span>
                                    </td>
                                    <td><?php echo e($section->max_products); ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo e($section->display_order); ?></span>
                                    </td>
                                    <td>
                                        <?php if($section->is_active): ?>
                                            <span class="badge bg-success">Hiển thị</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.product-sections.edit', $section)); ?>" 
                                               class="btn btn-sm btn-primary" title="Quản lý sản phẩm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.product-sections.destroy', $section)); ?>" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa section này?')"
                                                  class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
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
                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có section nào. Hãy tạo section đầu tiên!</p>
                    <a href="<?php echo e(route('admin.product-sections.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Section Mới
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/product-sections/index.blade.php ENDPATH**/ ?>