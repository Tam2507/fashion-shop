

<?php $__env->startSection('title', 'Cài Đặt Footer'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-cog"></i> Cài Đặt Footer</h1>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.footer-settings.update')); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Company Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-building"></i> Thông Tin Công Ty</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company_name" class="form-label">Tên công ty *</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="company_name" name="company_name" value="<?php echo e(old('company_name', $settings->company_name)); ?>" required>
                            <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="business_license" class="form-label">Giấy phép kinh doanh</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['business_license'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="business_license" name="business_license" value="<?php echo e(old('business_license', $settings->business_license)); ?>">
                            <?php $__errorArgs = ['business_license'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="company_description" class="form-label">Mô tả công ty</label>
                        <textarea class="form-control <?php $__errorArgs = ['company_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="company_description" name="company_description" rows="3"><?php echo e(old('company_description', $settings->company_description)); ?></textarea>
                        <?php $__errorArgs = ['company_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="address" name="address" rows="2"><?php echo e(old('address', $settings->address)); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="phone" name="phone" value="<?php echo e(old('phone', $settings->phone)); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="hotline" class="form-label">Hotline</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['hotline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="hotline" name="hotline" value="<?php echo e(old('hotline', $settings->hotline)); ?>">
                            <?php $__errorArgs = ['hotline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="email" name="email" value="<?php echo e(old('email', $settings->email)); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="working_hours" class="form-label">Giờ làm việc</label>
                        <textarea class="form-control <?php $__errorArgs = ['working_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="working_hours" name="working_hours" rows="2"><?php echo e(old('working_hours', $settings->working_hours)); ?></textarea>
                        <?php $__errorArgs = ['working_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-share-alt"></i> Mạng Xã Hội</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="social_facebook" class="form-label">Facebook</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['social_facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="social_facebook" name="social_facebook" value="<?php echo e(old('social_facebook', $settings->social_facebook)); ?>" 
                                   placeholder="https://facebook.com/yourpage">
                            <?php $__errorArgs = ['social_facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="social_instagram" class="form-label">Instagram</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['social_instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="social_instagram" name="social_instagram" value="<?php echo e(old('social_instagram', $settings->social_instagram)); ?>" 
                                   placeholder="https://instagram.com/yourpage">
                            <?php $__errorArgs = ['social_instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="social_youtube" class="form-label">YouTube</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['social_youtube'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="social_youtube" name="social_youtube" value="<?php echo e(old('social_youtube', $settings->social_youtube)); ?>" 
                                   placeholder="https://youtube.com/yourchannel">
                            <?php $__errorArgs = ['social_youtube'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="social_tiktok" class="form-label">TikTok</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['social_tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="social_tiktok" name="social_tiktok" value="<?php echo e(old('social_tiktok', $settings->social_tiktok)); ?>" 
                                   placeholder="https://tiktok.com/@yourpage">
                            <?php $__errorArgs = ['social_tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-credit-card"></i> Phương Thức Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                            $paymentMethods = ['visa', 'mastercard', 'jcb', 'atm', 'momo', 'zalopay'];
                            $selectedMethods = old('payment_methods', $settings->payment_methods ?? []);
                        ?>
                        
                        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]" 
                                       value="<?php echo e($method); ?>" id="payment_<?php echo e($method); ?>"
                                       <?php echo e(in_array($method, $selectedMethods) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="payment_<?php echo e($method); ?>">
                                    <?php echo e(ucfirst($method)); ?>

                                </label>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-copyright"></i> Bản Quyền</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="copyright_text" class="form-label">Text bản quyền</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['copyright_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="copyright_text" name="copyright_text" value="<?php echo e(old('copyright_text', $settings->copyright_text)); ?>" 
                               placeholder="© 2026 Fashion Shop - Thời Trang Cao Cấp. All rights reserved.">
                        <?php $__errorArgs = ['copyright_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Preview -->
            <div class="card sticky-top">
                <div class="card-header">
                    <h5><i class="fas fa-eye"></i> Xem Trước Footer</h5>
                </div>
                <div class="card-body">
                    <div class="footer-preview bg-dark text-white p-3" style="font-size: 0.8rem;">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h6 class="text-white"><?php echo e($settings->company_name ?: 'Fashion Shop'); ?></h6>
                                <?php if($settings->company_description): ?>
                                    <p class="mb-1"><?php echo e(Str::limit($settings->company_description, 100)); ?></p>
                                <?php endif; ?>
                                <?php if($settings->address): ?>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt"></i> <?php echo e($settings->address); ?></p>
                                <?php endif; ?>
                                <?php if($settings->phone): ?>
                                    <p class="mb-1"><i class="fas fa-phone"></i> <?php echo e($settings->phone); ?></p>
                                <?php endif; ?>
                                <?php if($settings->email): ?>
                                    <p class="mb-0"><i class="fas fa-envelope"></i> <?php echo e($settings->email); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if($settings->payment_methods): ?>
                            <div class="mb-2">
                                <small>Phương thức thanh toán:</small>
                                <div class="d-flex gap-1 mt-1">
                                    <?php $__currentLoopData = $settings->payment_methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($method)); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small><?php echo e($settings->copyright_text ?: '© 2026 Fashion Shop'); ?></small>
                            <div class="d-flex gap-2">
                                <?php if($settings->social_facebook): ?><i class="fab fa-facebook-f"></i><?php endif; ?>
                                <?php if($settings->social_instagram): ?><i class="fab fa-instagram"></i><?php endif; ?>
                                <?php if($settings->social_youtube): ?><i class="fab fa-youtube"></i><?php endif; ?>
                                <?php if($settings->social_tiktok): ?><i class="fab fa-tiktok"></i><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> Lưu Cài Đặt
        </button>
        <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Khôi Phục
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/footer-settings/index.blade.php ENDPATH**/ ?>