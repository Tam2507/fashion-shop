

<?php $__env->startSection('title', ($about->title ?? 'Về Chúng Tôi') . ' - Fashion Shop'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .about-page * {
        font-family: 'Times New Roman', Times, serif !important;
    }
    
    .about-page h1 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #8B3A3A;
    }
    
    .about-page h3 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #8B3A3A;
    }
    
    .about-page h4 {
        font-size: 1.4rem;
        font-weight: bold;
        color: #2B2B2B;
    }
    
    .about-page p,
    .about-page li {
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: justify;
    }
    
    .about-page .lead {
        font-size: 1.3rem;
        font-weight: 500;
    }
    
    .about-image {
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .about-image-main {
        height: 450px;
        margin-bottom: 1.5rem;
    }
    
    .about-image-secondary {
        height: 350px;
    }
</style>

<div class="container py-5 about-page">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="text-center mb-5"><?php echo e($about->title ?? 'Về Fashion Shop'); ?></h1>
            
            <?php if($about): ?>
                <!-- Images Section -->
                <?php if($about->image_1 || $about->image_2 || $about->image_3): ?>
                <div class="mb-5">
                    <?php if($about->image_1): ?>
                    <div class="mb-3">
                        <img src="<?php echo e(asset('storage/' . $about->image_1)); ?>" alt="About Image 1" class="about-image about-image-main">
                    </div>
                    <?php endif; ?>
                    
                    <?php if($about->image_2 || $about->image_3): ?>
                    <div class="row">
                        <?php if($about->image_2): ?>
                        <div class="col-md-6 mb-3">
                            <img src="<?php echo e(asset('storage/' . $about->image_2)); ?>" alt="About Image 2" class="about-image about-image-secondary">
                        </div>
                        <?php endif; ?>
                        
                        <?php if($about->image_3): ?>
                        <div class="col-md-6 mb-3">
                            <img src="<?php echo e(asset('storage/' . $about->image_3)); ?>" alt="About Image 3" class="about-image about-image-secondary">
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <?php if($about->intro): ?>
                        <h3 class="mb-4">Câu chuyện thương hiệu</h3>
                        <p class="lead"><?php echo e($about->intro); ?></p>
                        <?php endif; ?>
                        
                        <?php if($about->vision): ?>
                        <h4 class="mt-4 mb-3">Tầm nhìn</h4>
                        <p><?php echo e($about->vision); ?></p>
                        <?php endif; ?>
                        
                        <?php if($about->mission): ?>
                        <h4 class="mt-4 mb-3">Sứ mệnh</h4>
                        <p><?php echo e($about->mission); ?></p>
                        <?php endif; ?>
                        
                        <?php if($about->core_values): ?>
                        <h4 class="mt-4 mb-3">Giá trị cốt lõi</h4>
                        <ul>
                            <?php $__currentLoopData = explode("\n", $about->core_values); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(trim($value)): ?>
                                <li><?php echo nl2br(e(trim($value))); ?></li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Default content if no data -->
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="mb-4">Câu chuyện thương hiệu</h3>
                        <p class="lead">Fashion Shop được thành lập với sứ mệnh mang đến những sản phẩm thời trang cao cấp, phù hợp với phong cách hiện đại của người Việt Nam.</p>
                        
                        <h4 class="mt-4 mb-3">Tầm nhìn</h4>
                        <p>Trở thành thương hiệu thời trang hàng đầu Việt Nam, được khách hàng tin tưởng và yêu thích.</p>
                        
                        <h4 class="mt-4 mb-3">Sứ mệnh</h4>
                        <p>Cung cấp những sản phẩm thời trang chất lượng cao với giá cả hợp lý, giúp khách hàng tự tin thể hiện phong cách cá nhân.</p>
                        
                        <h4 class="mt-4 mb-3">Giá trị cốt lõi</h4>
                        <ul>
                            <li><strong>Chất lượng:</strong> Cam kết chất lượng sản phẩm tốt nhất</li>
                            <li><strong>Dịch vụ:</strong> Phục vụ khách hàng tận tâm, chu đáo</li>
                            <li><strong>Uy tín:</strong> Xây dựng niềm tin với khách hàng</li>
                            <li><strong>Đổi mới:</strong> Luôn cập nhật xu hướng thời trang mới</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/pages/about.blade.php ENDPATH**/ ?>