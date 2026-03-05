@extends('layouts.app')

@section('content')

<!-- Hero Carousel Banner -->
@php
    $productBanners = \App\Models\Banner::active()->forPage('products')->ordered()->get();
@endphp

@if($productBanners->count() > 0)
    <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            @foreach($productBanners as $index => $banner)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        
        <div class="carousel-inner">
            @foreach($productBanners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <a href="{{ $banner->link_url ?? route('products.index') }}" class="banner-link">
                        <div class="banner-container">
                            @if($banner->image)
                                <div class="banner-image" style="background-image: url('/storage/{{ $banner->image }}');"></div>
                            @else
                                <div class="banner-placeholder" style="background-color: #8B3A3A;"></div>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        @if($productBanners->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
    </div>
@else
    <!-- Fallback banner nếu không có banner nào -->
    <div class="banner-container mb-5">
        <div class="banner-placeholder" style="background: linear-gradient(135deg, #8B3A3A 0%, #A8563A 50%, #6B4C40 100%);">
            <div class="banner-content">
                <h1 style="color: white;">Sản Phẩm</h1>
                <p style="color: white;">Khám phá bộ sưu tập thời trang cao cấp</p>
            </div>
        </div>
    </div>
@endif

<!-- Category Filter (Optional) -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-shopping-bags"></i> Sản Phẩm</h2>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">Tất Cả</a>
        </div>
    </div>

    <!-- Search & Filters -->
    <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm tên, mô tả sản phẩm..." />
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Giá min" min="0" />
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Giá max" min="0" />
        </div>
        <div class="col-md-1 d-grid">
            <button class="btn btn-primary">Lọc</button>
        </div>
    </form>
</div>

<!-- Products Grid -->
<div class="row g-4 mb-5">
    @forelse($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card h-100">
            <!-- Product Image -->
            <div style="height: 280px; background: #f0f0f0; overflow: hidden; position: relative;">
                @php
                    $displayImage = $product->image ?? $product->images->first()->path ?? null;
                @endphp
                @if($displayImage)
                    <img src="/storage/{{ $displayImage }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $product->name }}" />
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <!-- Stock Badge -->
                <div style="position: absolute; top: 12px; right: 12px;">
                    @if($product->quantity > 0)
                        <span class="badge bg-success">Còn hàng</span>
                    @else
                        <span class="badge bg-danger">Hết hàng</span>
                    @endif
                </div>
                
                <!-- Hover Overlay -->
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s ease; display: flex; align-items: center; justify-content: center;" class="card-overlay">
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-search"></i> Xem Chi Tiết
                    </a>
                </div>
            </div>
            
            <!-- Card Content -->
            <div class="card-body d-flex flex-column">
                <p class="text-muted small mb-2">{{ $product->category->name ?? 'Chưa phân loại' }}</p>
                <h5 class="card-title text-truncate" style="flex-grow: 0;">{{ $product->name }}</h5>
                <p class="card-text text-muted small mb-3" style="flex-grow: 1;">{{ Str::limit($product->description, 70) }}</p>
                
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <p class="fw-bold text-primary mb-0" style="font-size: 1.4rem;">
                        {{ number_format($product->price, 0, ',', '.') }} ₫
                    </p>
                    @auth
                        @if($product->quantity > 0)
                            <form method="POST" action="{{ route('cart.add', $product->id) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>Hết hàng</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Đăng nhập</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-3">Chưa có sản phẩm nào.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mb-5">
    {{ $products->links() }}
</div>

<style>
    .card {
        position: relative;
        overflow: hidden;
    }
    
    .card:hover .card-overlay {
        opacity: 1 !important;
    }
    
    .card-overlay {
        z-index: 10;
    }

    /* Banner Styles - Same as Home Page */
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
    
    .banner-link:hover .banner-image {
        transform: scale(1.02);
    }
    
    .banner-content {
        text-align: center;
        padding: 2rem;
    }
    
    .banner-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }
    
    .banner-content p {
        font-size: 1.2rem;
        margin-bottom: 0;
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .banner-container {
            height: 300px;
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
    }
</style>

@endsection