

<?php $__env->startSection('title', 'Fashion Shop - Thời Trang Cao Cấp'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Banner Section -->
<div class="hero-banner mb-5">
    <?php
        $homeBanners = \App\Models\Banner::active()->forPage('home')->ordered()->get();
    ?>
    
    <?php if($homeBanners->count() > 0): ?>
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <?php $__currentLoopData = $homeBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="<?php echo e($index); ?>" 
                            class="<?php echo e($index === 0 ? 'active' : ''); ?>" aria-current="<?php echo e($index === 0 ? 'true' : 'false'); ?>" 
                            aria-label="Slide <?php echo e($index + 1); ?>"></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="carousel-inner">
                <?php $__currentLoopData = $homeBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                        <a href="<?php echo e($banner->link_url ?? route('products.index')); ?>" class="banner-link">
                            <div class="banner-container">
                                <?php if($banner->image): ?>
                                    <div class="banner-image" style="background-image: url('/storage/<?php echo e($banner->image); ?>');"></div>
                                <?php else: ?>
                                    <div class="banner-placeholder" style="background-color: #8B3A3A;">
                                        <div class="banner-content">
                                            <p style="color: white;">Chưa có ảnh banner</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <?php if($homeBanners->count() > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Fallback banner nếu không có banner nào -->
        <div class="banner-container">
            <div class="banner-placeholder" style="background: linear-gradient(135deg, #8B3A3A 0%, #A8563A 50%, #6B4C40 100%);">
                <div class="banner-content">
                    <h1 style="color: white;">Fashion Shop</h1>
                    <p style="color: white;">Thời Trang Cao Cấp</p>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-cta">XEM SẢN PHẨM</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Featured Products Section -->
<div class="container mb-5">
    <?php
        $productSections = \App\Models\ProductSection::active()->ordered()->with(['products' => function($query) {
            $query->where('is_active', true)->with(['category', 'images']);
        }])->get();
        
        // Debug info (xóa sau khi test)
        // dd([
        //     'total_sections' => $productSections->count(),
        //     'sections' => $productSections->map(function($s) {
        //         return [
        //             'name' => $s->name,
        //             'is_active' => $s->is_active,
        //             'products_count' => $s->products->count()
        //         ];
        //     })
        // ]);
    ?>
    
    <?php if($productSections->count() > 0): ?>
        <?php $__currentLoopData = $productSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($section->products->count() > 0): ?>
                <div class="mb-5 product-section" data-section-id="<?php echo e($section->id); ?>">
                    <div class="text-center mb-4">
                        <h2 class="section-title"><?php echo e($section->name); ?></h2>
                        <?php if($section->description): ?>
                            <p class="section-subtitle"><?php echo e($section->description); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="position-relative">
                        <!-- Carousel Controls -->
                        <?php if($section->products->count() > 4): ?>
                            <button class="carousel-nav carousel-nav-prev" onclick="window.slideSection(<?php echo e($section->id); ?>, -1); return false;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-nav carousel-nav-next" onclick="window.slideSection(<?php echo e($section->id); ?>, 1); return false;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                        
                        <div class="products-carousel-wrapper">
                            <div class="products-carousel" id="carousel-<?php echo e($section->id); ?>">
                                <?php $__currentLoopData = $section->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="product-carousel-item">
                                    <div class="card h-100 product-card">
                                <div class="product-image">
                                    <?php
                                        $displayImage = $product->image ?? $product->images->first()->image_path ?? null;
                                    ?>
                                    <?php if($displayImage): ?>
                                        <img src="/storage/<?php echo e($displayImage); ?>" class="card-img-top" alt="<?php echo e($product->name); ?>" />
                                    <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="product-overlay">
                                        <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-light btn-sm">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        <?php if(auth()->guard()->check()): ?>
                                            <?php if($product->quantity > 0): ?>
                                                <?php if($product->variants->count() > 0): ?>
                                                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary btn-sm" title="Chọn size và màu">
                                                        <i class="fas fa-shopping-bag"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-dark py-2 px-3" style="border-radius: 0;">
                                                        <i class="fas fa-shopping-bag"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="alert('Vui lòng đăng nhập để thêm vào giỏ hàng!'); window.location.href='<?php echo e(route('login')); ?>'">
                                                <i class="fas fa-shopping-bag"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if($product->quantity > 0): ?>
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
                                        <small class="text-muted">⭐ 4.5 (<?php echo e(rand(10, 50)); ?>)</small>
                                    </div>
                                </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <!-- Fallback: Show random products if no sections configured -->
        <div class="text-center mb-4">
            <h2 class="section-title">Sản Phẩm Nổi Bật</h2>
            <p class="section-subtitle">Những sản phẩm được yêu thích nhất</p>
        </div>
        
        <div class="row g-4">
            <?php
                $featuredProducts = \App\Models\Product::where('is_active', true)
                    ->with(['category', 'images'])
                    ->inRandomOrder()
                    ->limit(8)
                    ->get();
            ?>
            
            <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 product-card">
                    <div class="product-image">
                        <?php
                            $displayImage = $product->image ?? $product->images->first()->image_path ?? null;
                        ?>
                        <?php if($displayImage): ?>
                            <img src="/storage/<?php echo e($displayImage); ?>" class="card-img-top" alt="<?php echo e($product->name); ?>" />
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-overlay">
                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-light btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if($product->quantity > 0): ?>
                                    <?php if($product->variants->count() > 0): ?>
                                        <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary btn-sm" title="Chọn size và màu">
                                            <i class="fas fa-shopping-bag"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-shopping-bag"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Vui lòng đăng nhập để thêm vào giỏ hàng!'); window.location.href='<?php echo e(route('login')); ?>'">
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($product->quantity > 0): ?>
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
                            <small class="text-muted">⭐ 4.5 (<?php echo e(rand(10, 50)); ?>)</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
    
    <div class="text-center mt-4">
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bags"></i> Xem Tất Cả Sản Phẩm
        </a>
    </div>
</div>

<!-- Blog Section -->
<?php if(isset($latestPosts) && $latestPosts->count() > 0): ?>
<div class="container mb-5" id="blog">
    <div class="text-center mb-4">
        <h2 class="section-title">FASHION BLOG</h2>
        <p class="section-subtitle">ĐÓN ĐẦU XU HƯỚNG, ĐỊNH HÌNH PHONG CÁCH</p>
    </div>
    
    <div class="row g-4">
        <?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-4 col-md-6">
            <div class="blog-card h-100">
                <div class="blog-image">
                    <?php if($post->featured_image): ?>
                        <img src="/storage/<?php echo e($post->featured_image); ?>" alt="<?php echo e($post->title); ?>">
                    <?php else: ?>
                        <div class="blog-no-image">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    <?php endif; ?>
                    <div class="blog-overlay">
                        <a href="<?php echo e(route('posts.show', $post->slug)); ?>" class="btn btn-light">
                            <i class="fas fa-arrow-right"></i> Đọc Thêm
                        </a>
                    </div>
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="far fa-calendar"></i> <?php echo e($post->created_at->format('d/m/Y')); ?></span>
                        <span><i class="far fa-user"></i> <?php echo e($post->author->name ?? 'Admin'); ?></span>
                    </div>
                    <h4 class="blog-title"><?php echo e($post->title); ?></h4>
                    <p class="blog-excerpt"><?php echo e(Str::limit($post->excerpt ?? strip_tags($post->content), 120)); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="<?php echo e(route('home')); ?>#blog" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-book-open"></i> Xem Thêm Bài Viết
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Features Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon" style="background-color: #70AD47; color: white;">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5>Giao Hàng Nhanh</h5>
                    <p class="text-muted">Giao hàng trong 24h</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon" style="background-color: #5B9BD5; color: white;">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h5>Đổi Trả Dễ Dàng</h5>
                    <p class="text-muted">Đổi trả trong 30 ngày</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon" style="background-color: #C5504B; color: white;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5>Hỗ Trợ 24/7</h5>
                    <p class="text-muted">Tư vấn mọi lúc</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-item">
                    <div class="feature-icon" style="background-color: #D4A574; color: white;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>Thanh Toán An Toàn</h5>
                    <p class="text-muted">Bảo mật 100%</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hero Banner Styles */
    .hero-banner {
        margin-bottom: 3rem;
    }
    
    .banner-link {
        text-decoration: none;
        display: block;
        cursor: pointer;
    }
    
    .banner-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: transform 0.3s ease;
    }
    
    .banner-link:hover .banner-image {
        transform: scale(1.02);
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
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1;
    }
    
    .banner-content {
        text-align: center;
        padding: 2rem;
        max-width: 600px;
        position: relative;
        z-index: 2;
    }
    
    .banner-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        color: white;
    }
    
    .banner-content p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        color: white;
    }
    
    .btn-cta {
        background: #FFD700;
        color: #8B3A3A;
        border: none;
        padding: 15px 40px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cta:hover {
        background: white;
        color: #8B3A3A;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Carousel Controls */
    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    
    .carousel-control-prev {
        left: 20px;
    }
    
    .carousel-control-next {
        right: 20px;
    }
    
    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: rgba(255,255,255,0.4);
        border-color: rgba(255,255,255,0.6);
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 20px;
        height: 20px;
    }
    
    /* Carousel Indicators */
    .carousel-indicators {
        bottom: 20px;
    }
    
    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255,255,255,0.5);
        border: 2px solid rgba(255,255,255,0.8);
        transition: all 0.3s ease;
    }
    
    .carousel-indicators .active {
        background-color: #FFD700;
        border-color: #FFD700;
    }

    /* Product Cards */
    .product-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .product-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .no-image {
        width: 100%;
        height: 100%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 3rem;
    }
    
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover .product-overlay {
        opacity: 1;
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
    
    .price {
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--primary);
    }
    
    /* Section Styles */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #6c757d;
    }
    
    /* Features */
    .feature-item {
        padding: 2rem 1rem;
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
    }
    
    .feature-item:hover .feature-icon {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    /* Blog Section */
    .blog-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .blog-image {
        position: relative;
        height: 250px;
        overflow: hidden;
    }
    
    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .blog-card:hover .blog-image img {
        transform: scale(1.1);
    }
    
    .blog-no-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #8B3A3A 0%, #A8563A 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }
    
    .blog-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .blog-card:hover .blog-overlay {
        opacity: 1;
    }
    
    .blog-content {
        padding: 1.5rem;
    }
    
    .blog-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .blog-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .blog-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }
    
    .blog-excerpt {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 0;
    }
    
    /* Product Carousel */
    .product-section {
        position: relative;
    }
    
    .products-carousel-wrapper {
        overflow: hidden;
        padding: 0 10px;
    }
    
    .products-carousel {
        display: flex;
        gap: 1.5rem;
        transition: transform 0.5s ease;
    }
    
    .product-carousel-item {
        flex: 0 0 calc(25% - 1.125rem);
        min-width: calc(25% - 1.125rem);
    }
    
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--primary);
        color: var(--primary);
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .carousel-nav:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(139, 58, 58, 0.3);
    }
    
    .carousel-nav-prev {
        left: -25px;
    }
    
    .carousel-nav-next {
        right: -25px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .banner-container {
            height: 300px;
        }
        
        .banner-content h1 {
            font-size: 2rem;
        }
        
        .banner-content p {
            font-size: 1rem;
        }
        
        .btn-cta {
            padding: 12px 30px;
            font-size: 16px;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            width: 40px;
            height: 40px;
        }
        
        .carousel-control-prev {
            left: 10px;
        }
        
        .carousel-control-next {
            right: 10px;
        }
        
        .product-carousel-item {
            flex: 0 0 calc(50% - 0.75rem);
            min-width: calc(50% - 0.75rem);
        }
        
        .carousel-nav {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .carousel-nav-prev {
            left: -15px;
        }
        
        .carousel-nav-next {
            right: -15px;
        }
    }
    
    @media (max-width: 576px) {
        .product-carousel-item {
            flex: 0 0 100%;
            min-width: 100%;
        }
    }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_js'); ?>
<script>
// Product Section Carousel - Define globally first
const carouselStates = {};

window.slideSection = function(sectionId, direction) {
    const carousel = document.getElementById(`carousel-${sectionId}`);
    if (!carousel) {
        console.error('Carousel not found:', sectionId);
        return;
    }
    
    const items = carousel.querySelectorAll('.product-carousel-item');
    if (items.length === 0) {
        console.error('No items found in carousel:', sectionId);
        return;
    }
    
    const itemWidth = items[0].offsetWidth + 24; // width + gap
    
    if (!carouselStates[sectionId]) {
        carouselStates[sectionId] = { currentIndex: 0, autoPlayInterval: null };
    }
    
    const state = carouselStates[sectionId];
    
    // Determine items per view based on screen size
    let itemsPerView = 4;
    if (window.innerWidth <= 576) {
        itemsPerView = 1;
    } else if (window.innerWidth <= 768) {
        itemsPerView = 2;
    }
    
    const maxIndex = Math.max(0, items.length - itemsPerView);
    
    // Update index
    state.currentIndex += direction;
    
    // Loop around
    if (state.currentIndex < 0) {
        state.currentIndex = maxIndex;
    } else if (state.currentIndex > maxIndex) {
        state.currentIndex = 0;
    }
    
    // Apply transform
    carousel.style.transform = `translateX(-${state.currentIndex * itemWidth}px)`;
    
    console.log('Slide section:', sectionId, 'to index:', state.currentIndex);
    
    // Reset auto-play
    if (state.autoPlayInterval) {
        clearInterval(state.autoPlayInterval);
    }
    startAutoPlay(sectionId);
};

function startAutoPlay(sectionId) {
    const carousel = document.getElementById(`carousel-${sectionId}`);
    if (!carousel) return;
    
    const items = carousel.querySelectorAll('.product-carousel-item');
    
    // Determine items per view
    let itemsPerView = 4;
    if (window.innerWidth <= 576) {
        itemsPerView = 1;
    } else if (window.innerWidth <= 768) {
        itemsPerView = 2;
    }
    
    if (items.length <= itemsPerView) return; // No need for autoplay if all items visible
    
    if (!carouselStates[sectionId]) {
        carouselStates[sectionId] = { currentIndex: 0, autoPlayInterval: null };
    }
    
    const state = carouselStates[sectionId];
    
    // Clear existing interval
    if (state.autoPlayInterval) {
        clearInterval(state.autoPlayInterval);
    }
    
    state.autoPlayInterval = setInterval(() => {
        window.slideSection(sectionId, 1);
    }, 5000); // Auto-slide every 5 seconds
    
    console.log('Auto-play started for section:', sectionId);
}

// Initialize all carousels
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing carousels...');
    
    // Initialize banner carousel
    const carousel = document.getElementById('bannerCarousel');
    if (carousel) {
        const bsCarousel = new bootstrap.Carousel(carousel, {
            interval: 5000,
            wrap: true,
            touch: true
        });
        
        // Pause on hover
        carousel.addEventListener('mouseenter', function() {
            bsCarousel.pause();
        });
        
        carousel.addEventListener('mouseleave', function() {
            bsCarousel.cycle();
        });
    }
    
    // Start auto-play for all product carousels
    document.querySelectorAll('.product-section').forEach(section => {
        const sectionId = section.dataset.sectionId;
        console.log('Initializing section:', sectionId);
        
        startAutoPlay(sectionId);
        
        // Pause on hover
        section.addEventListener('mouseenter', function() {
            const state = carouselStates[sectionId];
            if (state && state.autoPlayInterval) {
                clearInterval(state.autoPlayInterval);
                console.log('Paused section:', sectionId);
            }
        });
        
        section.addEventListener('mouseleave', function() {
            startAutoPlay(sectionId);
        });
    });
    
    // Update cart count after adding to cart
    const cartForms = document.querySelectorAll('.add-to-cart-form');
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const actionUrl = this.action;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalHtml = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Add timeout to prevent infinite loading
            const timeoutId = setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                alert('Request timeout. Vui lòng thử lại!');
            }, 10000); // 10 second timeout
            
            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                clearTimeout(timeoutId);
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Invalid JSON response');
                }
                
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                
                if (data.success) {
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle"></i> Đã thêm vào giỏ hàng!
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    
                    // Update cart count
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                    
                    // Remove toast after hidden
                    toast.addEventListener('hidden.bs.toast', function() {
                        document.body.removeChild(toast);
                    });
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                
                console.error('Error:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/home.blade.php ENDPATH**/ ?>