

<?php $__env->startSection('content'); ?>

<!-- Hero Carousel Banner -->
<?php
    $productBanners = \App\Models\Banner::active()->forPage('products')->ordered()->get();
?>

<?php if($productBanners->count() > 0): ?>
    <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <?php $__currentLoopData = $productBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo e($index); ?>" 
                        class="<?php echo e($index === 0 ? 'active' : ''); ?>" aria-current="<?php echo e($index === 0 ? 'true' : 'false'); ?>" 
                        aria-label="Slide <?php echo e($index + 1); ?>"></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="carousel-inner">
            <?php $__currentLoopData = $productBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                    <a href="<?php echo e($banner->link_url ?? route('products.index')); ?>" class="banner-link">
                        <div class="banner-container">
                            <?php if($banner->image): ?>
                                <div class="banner-image" style="background-image: url('/storage/<?php echo e($banner->image); ?>');"></div>
                            <?php else: ?>
                                <div class="banner-placeholder" style="background-color: #8B3A3A;"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php if($productBanners->count() > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        <?php endif; ?>
    </div>
<?php else: ?>
    <!-- Fallback banner nếu không có banner nào -->
    <div class="banner-container mb-5">
        <div class="banner-placeholder" style="background: linear-gradient(135deg, #8B3A3A 0%, #A8563A 50%, #6B4C40 100%);">
            <div class="banner-content">
                <h1 style="color: white;">Sản Phẩm</h1>
                <p style="color: white;">Khám phá bộ sưu tập thời trang cao cấp</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Category Filter (Optional) -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-bags"></i> Sản Phẩm</h2>
        <div>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-primary">Tất Cả</a>
        </div>
    </div>

    <!-- Search & Filters -->
    <form method="GET" action="<?php echo e(route('products.index')); ?>" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="Tìm tên, mô tả sản phẩm..." />
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Tất cả danh mục</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>" class="form-control" placeholder="Giá min" min="0" />
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>" class="form-control" placeholder="Giá max" min="0" />
        </div>
        <div class="col-md-1 d-grid">
            <button class="btn btn-primary">Lọc</button>
        </div>
    </form>
</div>

<!-- Products Grid -->
<div class="row g-4 mb-5">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <?php
            $displayImage = $product->image ?? $product->images->first()?->path ?? null;
            $inStock = $product->quantity > 0 || $product->variants->where('stock_quantity', '>', 0)->count() > 0;
            $reviewCount = $product->approvedReviews->count();
            $avgRating = $reviewCount > 0 ? round($product->approvedReviews->avg('rating'), 1) : 0;
        ?>
        <div class="card h-100 product-card">
            <div class="product-image">
                <?php if($displayImage): ?>
                    <img src="/storage/<?php echo e($displayImage); ?>" class="card-img-top" alt="<?php echo e($product->name); ?>" />
                <?php else: ?>
                    <div class="no-image"><i class="fas fa-image"></i></div>
                <?php endif; ?>

                <div class="product-overlay">
                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if($inStock): ?>
                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary btn-sm" title="Chọn size và màu">
                                <i class="fas fa-shopping-bag"></i>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary btn-sm"
                            onclick="window.location.href='<?php echo e(route('login')); ?>'">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                    <?php endif; ?>
                </div>

                <?php if($inStock): ?>
                    <span class="badge bg-success product-badge">Còn hàng</span>
                <?php else: ?>
                    <span class="badge bg-danger product-badge">Hết hàng</span>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <p class="text-muted small mb-2"><?php echo e($product->category->name ?? 'Chưa phân loại'); ?></p>
                <h5 class="card-title"><?php echo e(Str::limit($product->name, 50)); ?></h5>
                <p class="card-text text-muted small"><?php echo e(Str::limit($product->description, 80)); ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="price"><?php echo e(number_format($product->price, 0, ',', '.')); ?> ₫</span>
                    <small class="text-muted">⭐ <?php echo e($avgRating); ?> (<?php echo e($reviewCount); ?>)</small>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12 text-center py-5">
        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-3">Chưa có sản phẩm nào.</p>
    </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mb-5">
    <?php echo e($products->links()); ?>

</div>

<style>
    /* ===== PRODUCT CARD - giống trang chủ ===== */
    .product-card {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: none;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.14);
    }
    .product-image {
        position: relative;
        height: 280px;
        overflow: hidden;
        background: #f5f5f5;
    }
    .product-image .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .product-card:hover .product-image .card-img-top { transform: scale(1.05); }
    .no-image {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: #ccc; font-size: 3rem;
    }
    .product-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 3;
    }
    .product-card:hover .product-overlay { opacity: 1; }
    .product-overlay .btn-primary {
        background: #8B3A3A !important;
        border-color: #8B3A3A !important;
        width: 44px; height: 44px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        padding: 0;
    }
    .product-badge {
        position: absolute;
        top: 12px; right: 12px;
        z-index: 2;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .price {
        font-size: 1.15rem;
        font-weight: bold;
        color: #8B3A3A;
    }

    /* Banner Styles */
    .banner-link {
        text-decoration: none;
        display: block;
        cursor: pointer;
    }
    .banner-container {
        position: relative;
        width: 100%;
        height: 500px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .banner-link:hover .banner-container {
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .banner-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .banner-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: transform 0.3s ease;
    }
    .banner-link:hover .banner-image { transform: scale(1.02); }
    .banner-content { text-align: center; padding: 2rem; }
    .banner-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        color: white;
    }
    .banner-content p { font-size: 1.2rem; margin-bottom: 0; color: white; }

    /* Carousel */
    .carousel-control-prev, .carousel-control-next {
        width: 60px; height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        top: 50%; transform: translateY(-50%);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    .carousel-control-prev { left: 20px; }
    .carousel-control-next { right: 20px; }
    .carousel-control-prev:hover, .carousel-control-next:hover {
        background: rgba(255,255,255,0.4);
        border-color: rgba(255,255,255,0.6);
    }
    .carousel-indicators { bottom: 20px; }
    .carousel-indicators [data-bs-target] {
        width: 12px; height: 12px;
        border-radius: 50%;
        background-color: rgba(255,255,255,0.5);
        border: 2px solid rgba(255,255,255,0.8);
        transition: all 0.3s ease;
    }
    .carousel-indicators .active { background-color: #FFD700; border-color: #FFD700; }

    @media (max-width: 768px) {
        .banner-container { height: 300px; }
        .carousel-control-prev, .carousel-control-next { width: 40px; height: 40px; }
        .carousel-control-prev { left: 10px; }
        .carousel-control-next { right: 10px; }
        .product-img-wrap { height: 220px; }
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/products/index.blade.php ENDPATH**/ ?>