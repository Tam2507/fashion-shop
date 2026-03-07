

<?php $__env->startSection('title', 'Quản Lý Bài Viết'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản Lý Bài Viết</h1>
        <a href="<?php echo e(route('admin.posts.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Bài Viết Mới
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if($posts->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Ảnh</th>
                                <th>Tiêu Đề</th>
                                <th>Tác Giả</th>
                                <th style="width: 120px;">Thứ Tự</th>
                                <th style="width: 100px;">Trạng Thái</th>
                                <th style="width: 150px;">Ngày Tạo</th>
                                <th style="width: 150px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php if($post->featured_image): ?>
                                        <img src="<?php echo e(asset('storage/' . $post->featured_image)); ?>" 
                                             alt="<?php echo e($post->title); ?>" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($post->title); ?></strong>
                                    <?php if($post->excerpt): ?>
                                        <br><small class="text-muted"><?php echo e(Str::limit($post->excerpt, 60)); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($post->author->name); ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo e($post->display_order); ?></span>
                                </td>
                                <td>
                                    <?php if($post->is_published): ?>
                                        <span class="badge bg-success">Đã xuất bản</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Nháp</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($post->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.posts.edit', $post)); ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.posts.destroy', $post)); ?>" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
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
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có bài viết nào. Hãy tạo bài viết đầu tiên!</p>
                    <a href="<?php echo e(route('admin.posts.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Bài Viết Mới
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/posts/index.blade.php ENDPATH**/ ?>