

<?php $__env->startSection('title', 'Cấu hình MoMo QR'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-wallet"></i> Cấu hình MoMo QR</h4>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Hướng dẫn lấy QR MoMo</h5>
                        <ol class="mb-0">
                            <li>Mở app MoMo trên điện thoại</li>
                            <li>Nhấn vào <strong>ảnh đại diện</strong> (góc trên bên trái)</li>
                            <li>Chọn <strong>"Mã QR của tôi"</strong></li>
                            <li>Chụp màn hình QR code</li>
                            <li>Upload ảnh bên dưới</li>
                        </ol>
                    </div>

                    <form action="<?php echo e(route('admin.settings.momo.upload')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload QR Code MoMo</label>
                            <input type="file" name="qr_image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Chấp nhận: JPG, PNG, WEBP</small>
                        </div>

                        <?php if(env('MOMO_STATIC_QR')): ?>
                            <div class="mb-4">
                                <label class="form-label fw-bold">QR hiện tại:</label>
                                <div class="text-center p-3 border rounded">
                                    <img src="<?php echo e(env('MOMO_STATIC_QR')); ?>" alt="MoMo QR" class="img-fluid" style="max-width: 300px;">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Số điện thoại MoMo (tùy chọn)</label>
                            <input type="text" name="momo_phone" class="form-control" placeholder="0123456789" value="<?php echo e(env('MOMO_PHONE', '')); ?>">
                            <small class="text-muted">Để hiển thị thông tin liên hệ</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Tên tài khoản MoMo (tùy chọn)</label>
                            <input type="text" name="momo_name" class="form-control" placeholder="NGUYEN VAN A" value="<?php echo e(env('MOMO_NAME', '')); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Lưu cấu hình
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/settings/momo.blade.php ENDPATH**/ ?>