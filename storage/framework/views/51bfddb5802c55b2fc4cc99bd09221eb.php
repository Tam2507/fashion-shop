

<?php $__env->startSection('title', 'Liên Hệ - Fashion Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <!-- Google Maps Section -->
    <?php if($contactInfo && $contactInfo->map_embed_url): ?>
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Vị Trí Của Chúng Tôi</h2>
            <div class="map-container">
                <iframe src="<?php echo e($contactInfo->map_embed_url); ?>" 
                        width="100%" 
                        height="450" 
                        style="border:0; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-5">Liên Hệ Với Chúng Tôi</h1>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-map-marker-alt text-primary"></i> Địa chỉ</h5>
                            <p class="card-text">
                                <?php echo e($contactInfo->address ?? 'Lầu 1-2, 123 Nguyễn Văn Cừ'); ?><br>
                                <?php echo e($contactInfo->city ?? 'Quận 1, TP. Hồ Chí Minh'); ?><br>
                                <?php echo e($contactInfo->country ?? 'Việt Nam'); ?>

                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-phone text-primary"></i> Điện thoại</h5>
                            <p class="card-text">
                                Hotline: <strong><?php echo e($contactInfo->hotline ?? '1900.1234'); ?></strong><br>
                                <?php if($contactInfo->phone): ?>
                                Mua hàng online: <strong><?php echo e($contactInfo->phone); ?></strong><br>
                                <?php endif; ?>
                                <?php if($contactInfo->working_hours): ?>
                                Thời gian: <?php echo e($contactInfo->working_hours); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-envelope text-primary"></i> Email</h5>
                            <p class="card-text">
                                Email: <a href="mailto:<?php echo e($contactInfo->email ?? 'info@fashionshop.vn'); ?>"><?php echo e($contactInfo->email ?? 'info@fashionshop.vn'); ?></a><br>
                                <?php if($contactInfo->support_email): ?>
                                Hỗ trợ: <a href="mailto:<?php echo e($contactInfo->support_email); ?>"><?php echo e($contactInfo->support_email); ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-clock text-primary"></i> Giờ làm việc</h5>
                            <p class="card-text">
                                <?php echo e($contactInfo->weekday_hours ?? 'Thứ 2 - Thứ 6: 8:00 - 18:00'); ?><br>
                                <?php echo e($contactInfo->weekend_hours ?? 'Thứ 7 - Chủ nhật: 9:00 - 17:00'); ?><br>
                                <?php echo e($contactInfo->holiday_note ?? 'Lễ tết: Theo thông báo'); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Gửi tin nhắn cho chúng tôi</h5>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('contact.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên *</label>
                                <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" value="<?php echo e(old('name')); ?>" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" value="<?php echo e(old('email')); ?>" required>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" value="<?php echo e(old('phone')); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Chủ đề *</label>
                            <input type="text" name="subject" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subject" value="<?php echo e(old('subject')); ?>" required>
                            <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Nội dung *</label>
                            <textarea name="message" class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="message" rows="5" required><?php echo e(old('message')); ?></textarea>
                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .map-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .map-container:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .map-container iframe {
        display: block;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/pages/contact.blade.php ENDPATH**/ ?>