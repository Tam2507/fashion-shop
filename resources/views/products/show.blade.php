@extends('layouts.app')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-lg-5">
        <div class="product-gallery">
            <div class="main-image mb-3" style="background: #f5f1e8; border-radius: 4px; overflow: hidden; height: 600px; display: flex; align-items: center; justify-content: center;">
                @php $first = $product->image ?? ($product->images->first()->path ?? null); @endphp
                @if($first)
                    <img src="/storage/{{ $first }}" id="mainImage" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="{{ $product->name }}">
                @else
                    <i class="fas fa-image text-muted" style="font-size: 5rem;"></i>
                @endif
            </div>
            <div class="thumbnails d-flex gap-2">
                @if($product->image)
                    <div class="thumbnail rounded" style="width: 80px; height: 80px; cursor: pointer; overflow: hidden;" data-color="{{ $product->image_color ?? '' }}">
                        <img src="/storage/{{ $product->image }}" class="w-100 h-100" style="object-fit: cover;" alt="Thumbnail" onclick="updateMainImage(this)">
                    </div>
                @endif
                @foreach($product->images as $img)
                    <div class="thumbnail rounded" style="width: 80px; height: 80px; cursor: pointer; overflow: hidden;" data-color="{{ $img->color ?? '' }}">
                        <img src="/storage/{{ $img->path }}" class="w-100 h-100" style="object-fit: cover;" alt="Thumbnail" onclick="updateMainImage(this)">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <div class="product-details">
            <p class="text-muted small" style="letter-spacing: 1px; text-transform: uppercase;">{{ $product->category->name ?? 'Fashion' }}</p>
            <h1 class="mb-2" style="font-size: 2rem; font-weight: 700;">{{ $product->name }}</h1>
            <p class="text-muted small mb-4">SKU: PROD-{{ $product->id }}</p>
            
            <div class="d-flex align-items-baseline gap-3 mb-4">
                <h2 class="text-primary" style="font-size: 2.5rem; margin: 0;">{{ number_format($product->price, 0, ',', '.') }}₫</h2>
                <span class="badge {{ $product->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                </span>
            </div>
            
            <p class="text-muted mb-4" style="line-height: 1.8;">{{ $product->description }}</p>
            <hr class="my-4">
            
            @if($availableColors->count() > 0 || $availableSizes->count() > 0)
            <div class="variant-selection-card mb-4 p-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                @if($availableColors->count() > 0)
                <div class="mb-4">
                    <label class="form-label fw-bold mb-3" style="font-size: 1.1rem; color: #2c3e50;">
                        <i class="fas fa-palette me-2"></i>Chọn Màu Sắc
                    </label>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($availableColors as $color)
                        <button type="button" class="color-btn-modern" data-color="{{ $color }}">
                            <span class="color-name">{{ ucfirst($color) }}</span>
                            <span class="checkmark"><i class="fas fa-check"></i></span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @php
                    // Check if product is in "Phụ kiện" category - accessories don't need size
                    $isAccessory = $product->category && 
                                   (stripos($product->category->name, 'phụ kiện') !== false || 
                                    stripos($product->category->name, 'accessory') !== false ||
                                    stripos($product->category->name, 'accessories') !== false);
                    
                    // Standard sizes to always display
                    $standardSizes = ['S', 'M', 'L', 'XL', 'XXL'];
                @endphp
                
                @if(!$isAccessory)
                <div class="mb-3">
                    <label class="form-label fw-bold mb-3" style="font-size: 1.1rem; color: #2c3e50;">
                        <i class="fas fa-ruler me-2"></i>Chọn Kích Thước
                    </label>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($standardSizes as $size)
                        <button type="button" class="size-btn-modern" data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                </div>
                
                <div id="variantInfo" style="display: none;" class="mt-3">
                    <div class="alert alert-success mb-0" style="border-radius: 8px; border-left: 4px solid #28a745;">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Đã chọn:</strong> <span id="selectedVariantText"></span>
                    </div>
                </div>
                @endif
            </div>
            @endif
            
            <div class="mb-4">
                <label class="form-label fw-bold small" style="text-transform: uppercase; letter-spacing: 0.5px;">Số Lượng</label>
                <div class="d-flex align-items-center gap-0">
                    <button class="btn btn-outline-dark" style="width: 50px; height: 50px; border-radius: 0; font-size: 1.5rem; padding: 0; display: flex; align-items: center; justify-content: center;" onclick="decreaseQty()">−</button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->quantity }}" class="form-control" style="width: 80px; border-radius: 0; text-align: center; border-left: none; border-right: none; font-weight: 600;">
                    <button class="btn btn-outline-dark" style="width: 50px; height: 50px; border-radius: 0; font-size: 1.5rem; padding: 0; display: flex; align-items: center; justify-content: center;" onclick="increaseQty()">+</button>
                </div>
            </div>
            
            <hr class="my-4">
            
            @auth
                @if($product->quantity > 0 || $product->variants->count())
                <form method="POST" action="{{ route('cart.add', $product->id) }}" id="addToCartForm" class="mb-3">
                    @csrf
                    <input type="hidden" name="quantity" id="cartQuantity" value="1">
                    <input type="hidden" name="variant_id" id="cartVariantId" value="">
                    <div class="row g-2">
                        <div class="col-6">
                                <button type="submit" class="btn btn-dark w-100 py-3 fw-bold" style="border-radius: 0; letter-spacing: 1px;">
                                    <i class="fas fa-shopping-bag"></i> THÊM VÀO GIỎ HÀNG
                                </button>
                            </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-dark w-100 py-3 fw-bold" style="border-radius: 0; letter-spacing: 1px;" onclick="buyNow()">
                                MUA NGAY
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="row g-2 mb-4">
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-outline-dark w-100 py-3 fw-bold" style="border-radius: 0; letter-spacing: 1px;" id="wishlistBtn">
                            <i class="fas fa-heart"></i> YÊU THÍCH
                        </button>
                        <button class="btn btn-outline-secondary w-100 py-3 fw-bold" style="border-radius: 0; letter-spacing: 1px;" data-bs-toggle="collapse" data-bs-target="#reviewForm">
                            <i class="fas fa-star"></i> GỬI ĐÁNH GIÁ
                        </button>
                    </div>
                </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-dark w-100 py-3 fw-bold mb-3" style="border-radius: 0; letter-spacing: 1px;">
                    <i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP ĐỂ MUA
                </a>
            @endauth
            
            <div class="d-flex align-items-center gap-3 pt-3 border-top">
                <span class="text-muted small" style="text-transform: uppercase; letter-spacing: 0.5px;">CHIA SẺ</span>
                <a href="#" class="btn btn-sm btn-light rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fab fa-facebook text-muted"></i>
                </a>
                <a href="#" class="btn btn-sm btn-light rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fab fa-twitter text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <ul class="nav nav-tabs border-bottom-2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active fw-bold text-dark" data-bs-toggle="tab" href="#description">CHI TIẾT SẢN PHẨM</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold text-dark" data-bs-toggle="tab" href="#shipping">GIAO HÀNG & HOÀN HÀNG</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold text-dark" data-bs-toggle="tab" href="#reviews">ĐÁNH GIÁ</a>
            </li>
        </ul>
        <div class="tab-content pt-4">
            <div id="description" class="tab-pane fade show active">
                <p>{{ $product->description }}</p>
                <p class="text-muted">Chất liệu: 100% Cotton | Hướng dẫn giặt: Giặt với nước lạnh | Xuất xứ: Việt Nam</p>
            </div>
            <div id="shipping" class="tab-pane fade">
                <p>Giao hàng miễn phí cho đơn hàng trên 500,000₫. Thời gian giao hàng từ 2-5 ngày làm việc.</p>
                <p>Chấp nhận hoàn hàng trong 30 ngày nếu không hài lòng.</p>
            </div>
            <div id="reviews" class="tab-pane fade">
                @foreach($product->reviews()->where('approved', true)->latest()->get() as $r)
                    <div class="mb-3">
                        <strong>{{ $r->user->name ?? 'Khách' }}</strong> — <small class="text-muted">{{ $r->created_at->format('d/m/Y') }}</small>
                        <div>Rating: {{ $r->rating }} / 5</div>
                        <p>{{ $r->comment }}</p>
                    </div>
                @endforeach
                @if($product->reviews()->where('approved', true)->count() == 0)
                    <p class="text-muted">Chưa có đánh giá nào.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($relatedProducts->count())
<div class="row">
    <div class="col-12">
        <h2 class="mb-4"><i class="fas fa-link"></i> Sản Phẩm Liên Quan</h2>
    </div>
    <div class="row g-4">
        @foreach($relatedProducts as $rp)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100">
                <div style="height: 280px; background: #f0f0f0; overflow: hidden; position: relative;">
                    @php
                        $displayImage = $rp->image ?? $rp->images->first()->path ?? null;
                    @endphp
                    @if($displayImage)
                        <img src="/storage/{{ $displayImage }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $rp->name }}" />
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div style="position: absolute; top: 12px; right: 12px;">
                        <span class="badge bg-success">Còn hàng</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">{{ $rp->category->name ?? 'N/A' }}</p>
                    <h6 class="card-title text-truncate">{{ $rp->name }}</h6>
                    <p class="fw-bold text-primary mb-3">{{ number_format($rp->price, 0, ',', '.') }} ₫</p>
                    <a href="{{ route('products.show', $rp->id) }}" class="btn btn-primary w-100 btn-sm">Xem Chi Tiết</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<script>
let selectedColor = '';
let selectedSize = '';
const variants = @json($product->variants);
const isAccessory = {{ $isAccessory ? 'true' : 'false' }};

console.log('Available variants:', variants);
console.log('Is accessory product:', isAccessory);

function increaseQty() {
    const qtyInput = document.getElementById('quantity');
    const max = parseInt(qtyInput.max) || 999;
    if (parseInt(qtyInput.value) < max) {
        qtyInput.value = parseInt(qtyInput.value) + 1;
        document.getElementById('cartQuantity').value = qtyInput.value;
    }
}

function decreaseQty() {
    const qtyInput = document.getElementById('quantity');
    if (parseInt(qtyInput.value) > 1) {
        qtyInput.value = parseInt(qtyInput.value) - 1;
        document.getElementById('cartQuantity').value = qtyInput.value;
    }
}

function buyNow() {
    // Check if color/size selection UI is visible
    const hasColorButtons = document.querySelectorAll('.color-btn-modern').length > 0;
    const hasSizeButtons = document.querySelectorAll('.size-btn-modern').length > 0;
    
    // If color selection UI exists, color must be selected
    if (hasColorButtons && !selectedColor) {
        alert('Vui lòng chọn màu sắc trước khi mua!');
        const colorSection = document.querySelector('.color-btn-modern');
        if (colorSection) {
            colorSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return false;
    }
    
    // If size selection UI exists, size must be selected
    if (hasSizeButtons && !selectedSize) {
        alert('Vui lòng chọn kích thước trước khi mua!');
        const sizeSection = document.querySelector('.size-btn-modern');
        if (sizeSection) {
            sizeSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return false;
    }
    
    // If both selections are required, check variant_id
    if ((hasColorButtons || hasSizeButtons) && variants.length > 0) {
        const variantId = document.getElementById('cartVariantId').value;
        if (!variantId) {
            alert('Không tìm thấy sản phẩm phù hợp với lựa chọn của bạn!');
            return false;
        }
    }
    
    // If validation passed, submit form
    document.getElementById('cartQuantity').value = document.getElementById('quantity').value;
    document.getElementById('addToCartForm').submit();
    setTimeout(() => {
        window.location.href = '{{ route('cart.index') }}';
    }, 500);
}

function updateMainImage(thumb) {
    document.getElementById('mainImage').src = thumb.src;
    
    // Update active state for thumbnails
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumb.closest('.thumbnail').classList.add('active');
}

document.getElementById('quantity').addEventListener('change', function() {
    document.getElementById('cartQuantity').value = this.value;
});

// Color selection - filter images by color with modern animation
document.querySelectorAll('.color-btn-modern').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update button states with animation
        document.querySelectorAll('.color-btn-modern').forEach(b => {
            b.classList.remove('active');
        });
        this.classList.add('active');
        
        selectedColor = this.dataset.color;
        updateVariantInfo();
        
        // Filter images by color with fade effect
        const color = this.dataset.color;
        const thumbnails = document.querySelectorAll('.thumbnail');
        let colorImages = Array.from(thumbnails).filter(t => t.dataset.color === color);
        
        // Add fade out effect
        thumbnails.forEach(t => {
            t.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            t.style.opacity = '0.3';
        });
        
        setTimeout(() => {
            // If no color-specific images, show all
            if (colorImages.length === 0) {
                thumbnails.forEach(t => {
                    t.style.display = 'block';
                    t.style.opacity = '1';
                });
            } else {
                // Hide all, then show color-specific
                thumbnails.forEach(t => {
                    t.style.display = 'none';
                    t.style.opacity = '1';
                });
                colorImages.forEach(t => {
                    t.style.display = 'block';
                    t.style.opacity = '1';
                });
                
                // Update main image to first visible with smooth transition
                if (colorImages.length > 0) {
                    const firstImg = colorImages[0].querySelector('img');
                    if (firstImg) {
                        const mainImg = document.getElementById('mainImage');
                        mainImg.style.transition = 'opacity 0.3s ease';
                        mainImg.style.opacity = '0';
                        
                        setTimeout(() => {
                            mainImg.src = firstImg.src;
                            mainImg.style.opacity = '1';
                            updateMainImage(firstImg);
                        }, 300);
                    }
                }
            }
        }, 300);
    });
});

// Size selection with modern animation
document.querySelectorAll('.size-btn-modern').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update button states with animation
        document.querySelectorAll('.size-btn-modern').forEach(b => {
            b.classList.remove('active');
        });
        this.classList.add('active');
        
        selectedSize = this.dataset.size;
        updateVariantInfo();
    });
});

function updateVariantInfo() {
    const variantInfo = document.getElementById('variantInfo');
    const variantText = document.getElementById('selectedVariantText');
    const cartVariantId = document.getElementById('cartVariantId');
    
    if (selectedColor || selectedSize) {
        let text = [];
        if (selectedSize) text.push('Kích thước: ' + selectedSize);
        if (selectedColor) text.push('Màu sắc: ' + selectedColor);
        variantText.textContent = text.join(' • ');
        
        if (variantInfo) {
            variantInfo.style.display = 'block';
            variantInfo.style.animation = 'slideInUp 0.3s ease';
        }
        
        // Find matching variant
        const matchingVariant = variants.find(v => {
            const sizeMatch = !selectedSize || v.size === selectedSize;
            const colorMatch = !selectedColor || v.color === selectedColor;
            return sizeMatch && colorMatch;
        });
        
        console.log('Looking for variant with:', { selectedColor, selectedSize });
        console.log('Found variant:', matchingVariant);
        
        if (matchingVariant) {
            cartVariantId.value = matchingVariant.id;
            console.log('Set variant ID to:', matchingVariant.id);
        } else {
            cartVariantId.value = '';
            console.log('No matching variant found');
        }
    } else {
        if (variantInfo) {
            variantInfo.style.display = 'none';
        }
        cartVariantId.value = '';
    }
}

// Form validation - require color and size selection before adding to cart
const addToCartForm = document.getElementById('addToCartForm');
if (addToCartForm) {
    addToCartForm.addEventListener('submit', function(e) {
        // Check if color/size selection UI is visible
        const hasColorButtons = document.querySelectorAll('.color-btn-modern').length > 0;
        const hasSizeButtons = document.querySelectorAll('.size-btn-modern').length > 0;
        
        console.log('=== VALIDATION DEBUG ===');
        console.log('Has color buttons:', hasColorButtons);
        console.log('Has size buttons:', hasSizeButtons);
        console.log('Is accessory:', isAccessory);
        console.log('Selected color:', selectedColor);
        console.log('Selected size:', selectedSize);
        console.log('Variants:', variants);
        console.log('Variant ID:', document.getElementById('cartVariantId').value);
        
        // If color selection UI exists, color must be selected
        if (hasColorButtons && !selectedColor) {
            e.preventDefault();
            alert('Vui lòng chọn màu sắc trước khi thêm vào giỏ hàng!');
            const colorSection = document.querySelector('.color-btn-modern');
            if (colorSection) {
                colorSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }
        
        // If size selection UI exists (non-accessory), size must be selected
        if (hasSizeButtons && !selectedSize) {
            e.preventDefault();
            alert('Vui lòng chọn kích thước trước khi thêm vào giỏ hàng!');
            const sizeSection = document.querySelector('.size-btn-modern');
            if (sizeSection) {
                sizeSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }
        
        console.log('Validation passed, submitting form...');
    });
}

// Wishlist button
const wishlistBtn = document.getElementById('wishlistBtn');
if (wishlistBtn) {
    wishlistBtn.addEventListener('click', function(e){
        e.preventDefault();
        fetch("{{ route('wishlist.store', $product->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        }).then(r => {
            if (r.ok) {
                // Show success message
                alert('Đã thêm vào danh sách yêu thích!');
                location.reload();
            }
        });
    });
}

// Add smooth scroll to thumbnails
document.querySelectorAll('.thumbnail img').forEach(img => {
    img.addEventListener('click', function() {
        updateMainImage(this);
    });
});
</script>

<style>
    .product-gallery {
        position: sticky;
        top: 100px;
    }
    
    /* Modern Color Button Styles */
    .color-btn-modern {
        position: relative;
        padding: 12px 24px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #2c3e50;
        min-width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .color-btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #3498db;
    }
    
    .color-btn-modern.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .color-btn-modern .checkmark {
        display: none;
    }
    
    .color-btn-modern.active .checkmark {
        display: inline-block;
        animation: checkmarkPop 0.3s ease;
    }
    
    @keyframes checkmarkPop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    /* Modern Size Button Styles */
    .size-btn-modern {
        padding: 12px 20px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 700;
        font-size: 1.1rem;
        color: #2c3e50;
        min-width: 70px;
        text-align: center;
    }
    
    .size-btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #e74c3c;
    }
    
    .size-btn-modern.active {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-color: #f5576c;
        color: white;
        box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
    }
    
    /* Variant Selection Card */
    .variant-selection-card {
        animation: slideInUp 0.5s ease;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Thumbnail Styles */
    .thumbnail {
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .thumbnail:hover {
        border-color: #3498db;
        transform: scale(1.05);
    }
    
    .thumbnail.active {
        border-color: #e74c3c;
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
    
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid var(--primary);
        border-top: none;
        border-left: none;
        border-right: none;
    }
</style>
@endsection