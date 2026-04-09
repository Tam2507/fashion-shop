@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-2">
                <i class="fas fa-heart text-danger"></i> Danh Sách Yêu Thích
            </h1>
            <p class="text-muted">Các sản phẩm bạn đã lưu để xem sau</p>
        </div>
    </div>
    
    @if($wishlists->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-heart-broken text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                        <h4 class="mt-4 mb-3">Danh sách yêu thích trống</h4>
                        <p class="text-muted mb-4">Bạn chưa thêm sản phẩm nào vào danh sách yêu thích</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Khám Phá Sản Phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fas fa-box me-2"></i>{{ $wishlists->count() }} sản phẩm
                    </p>
                    <form method="POST" action="{{ route('wishlist.clear') }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa tất cả sản phẩm khỏi danh sách yêu thích?')">
                            <i class="fas fa-trash me-1"></i>Xóa Tất Cả
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($wishlists as $wishlist)
            @php
                $product = $wishlist->product;
                $displayImage = $product->image ?? $product->images->first()->path ?? null;
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm wishlist-card">
                    <div class="position-relative">
                        <div class="product-image-wrapper" style="height: 300px; background: #f5f5f5; overflow: hidden;">
                            @if($displayImage)
                                <img src="/storage/{{ $displayImage }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $product->name }}" />
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Remove from wishlist button -->
                        <form method="POST" action="{{ route('wishlist.destroy', $product->id) }}" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 40px; height: 40px;" title="Xóa khỏi yêu thích">
                                <i class="fas fa-times text-danger"></i>
                            </button>
                        </form>
                        
                        @if($product->quantity > 0)
                            <span class="badge bg-success position-absolute bottom-0 start-0 m-2">Còn hàng</span>
                        @else
                            <span class="badge bg-danger position-absolute bottom-0 start-0 m-2">Hết hàng</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            <i class="fas fa-tag me-1"></i>{{ $product->category->name ?? 'Chưa phân loại' }}
                        </p>
                        <h5 class="card-title mb-2">
                            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                {{ Str::limit($product->name, 50) }}
                            </a>
                        </h5>
                        <p class="text-muted small mb-3">{{ Str::limit($product->description, 80) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 text-primary mb-0">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                            @php
                                $reviewCount = $product->reviews()->where('approved', true)->count();
                                $avgRating = $reviewCount > 0 ? $product->reviews()->where('approved', true)->avg('rating') : 0;
                            @endphp
                            <small class="text-muted">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}" style="font-size:11px;"></i>
                                @endfor
                                ({{ $reviewCount }})
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Xem Chi Tiết
                            </a>
                            @if($product->quantity > 0)
                                @if($product->variants->count() > 0)
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-bag me-1"></i>Chọn Mua
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('cart.add', $product->id) }}" class="add-to-cart-form-wishlist">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-shopping-bag me-1"></i>Thêm Vào Giỏ
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-ban me-1"></i>Hết Hàng
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light border-0 text-muted small">
                        <i class="fas fa-clock me-1"></i>Đã thêm: {{ $wishlist->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.wishlist-card {
    transition: all 0.3s ease;
}

.wishlist-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
}

.product-image-wrapper img {
    transition: transform 0.3s ease;
}

.wishlist-card:hover .product-image-wrapper img {
    transform: scale(1.05);
}
</style>

<script>
// Handle add to cart from wishlist
document.querySelectorAll('.add-to-cart-form-wishlist').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const actionUrl = this.action;
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalHtml = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
            
            if (data.success) {
                submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Đã Thêm';
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-success');
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalHtml;
                    submitBtn.classList.remove('btn-success');
                    submitBtn.classList.add('btn-primary');
                }, 2000);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        });
    });
});
</script>
@endsection
