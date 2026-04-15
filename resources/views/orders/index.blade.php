@extends('layouts.app')

@section('content')
<style>
.order-page { background: #f7f8fc; min-height: 100vh; padding: 40px 0; }
.stat-card {
    border-radius: 16px;
    padding: 20px 24px;
    border: none;
    transition: transform .2s;
}
.stat-card:hover { transform: translateY(-3px); }
.stat-card .stat-num { font-size: 2rem; font-weight: 700; line-height: 1; }
.stat-card .stat-label { font-size: 0.8rem; opacity: .85; margin-top: 4px; }

.filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
.filter-tab {
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1.5px solid #e0e0e0;
    background: white;
    color: #555;
    text-decoration: none;
    transition: all .2s;
}
.filter-tab:hover { border-color: #8B3A3A; color: #8B3A3A; }
.filter-tab.active { background: #8B3A3A; border-color: #8B3A3A; color: white; }

.order-card {
    background: white;
    border-radius: 16px;
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    margin-bottom: 16px;
    overflow: hidden;
    transition: box-shadow .2s;
}
.order-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.1); }

.order-card-header {
    padding: 16px 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}
.order-id { font-weight: 700; color: #1a1a1a; font-size: 0.95rem; }
.order-date { color: #999; font-size: 0.82rem; }

.status-badge {
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: .3px;
}
.status-pending    { background: #fff3cd; color: #856404; }
.status-processing { background: #cfe2ff; color: #084298; }
.status-shipping   { background: #d1ecf1; color: #0c5460; }
.status-delivered  { background: #d1e7dd; color: #0a3622; }
.status-cancelled  { background: #f8d7da; color: #842029; }

.order-card-body { padding: 16px 24px; }

.product-thumb {
    width: 64px; height: 64px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #f0f0f0;
}
.product-thumb-placeholder {
    width: 64px; height: 64px;
    border-radius: 10px;
    background: #f5f5f5;
    display: flex; align-items: center; justify-content: center;
    color: #ccc; font-size: 1.4rem;
}
.more-badge {
    width: 64px; height: 64px;
    border-radius: 10px;
    background: #f0f0f0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; color: #666; font-weight: 600;
}

.order-total { font-size: 1.15rem; font-weight: 700; color: #8B3A3A; }
.btn-detail {
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1.5px solid #8B3A3A;
    color: #8B3A3A;
    background: white;
    text-decoration: none;
    transition: all .2s;
}
.btn-detail:hover { background: #8B3A3A; color: white; }

.empty-state { text-align: center; padding: 80px 20px; }
.empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 16px; }
.empty-state h5 { color: #999; font-weight: 500; }
</style>

<div class="order-page">
<div class="container" style="max-width: 860px;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0" style="color:#1a1a1a;">Lịch sử mua hàng</h3>
            <p class="text-muted mb-0" style="font-size:.9rem;">Theo dõi và quản lý đơn hàng của bạn</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-detail">
            <i class="fas fa-plus me-1"></i>Mua thêm
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="stat-num text-white">{{ $orders->total() }}</div>
                <div class="stat-label text-white">Tổng đơn hàng</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                <div class="stat-num text-white">{{ $orders->where('status','delivered')->count() }}</div>
                <div class="stat-label text-white">Đã nhận hàng</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#f7971e,#ffd200);">
                <div class="stat-num text-dark">{{ $orders->whereIn('status',['pending','processing','shipping'])->count() }}</div>
                <div class="stat-label text-dark">Đang xử lý</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#8B3A3A,#C41E3A);">
                <div class="stat-num text-white" style="font-size:1.3rem;">{{ number_format($orders->sum('total_price'),0,',','.') }}₫</div>
                <div class="stat-label text-white">Tổng chi tiêu</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    @php $cur = request('status',''); @endphp
    <div class="filter-tabs">
        @foreach(['' => 'Tất cả', 'pending' => 'Chờ xác nhận', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'delivered' => 'Đã nhận', 'cancelled' => 'Đã hủy'] as $val => $label)
        <a href="{{ route('orders.index', $val ? ['status'=>$val] : []) }}"
           class="filter-tab {{ $cur === $val ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- Orders --}}
    @forelse($orders as $order)
    @php
        $statusClass = match($order->status) {
            'pending'    => 'status-pending',
            'processing' => 'status-processing',
            'shipping'   => 'status-shipping',
            'delivered'  => 'status-delivered',
            'cancelled'  => 'status-cancelled',
            default      => 'status-pending',
        };
    @endphp
    <div class="order-card">
        <div class="order-card-header">
            <div>
                <span class="order-id">Đơn #{{ $order->id }}</span>
                <span class="order-date ms-3"><i class="far fa-clock me-1"></i>{{ $order->created_at->format('d/m/Y · H:i') }}</span>
            </div>
            <span class="status-badge {{ $statusClass }}">{{ $order->status_label }}</span>
        </div>
        <div class="order-card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                {{-- Thumbnails --}}
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @foreach($order->items->take(4) as $item)
                    @php $img = $item->product->image ?? ($item->product->images->first()->path ?? null); @endphp
                    @if($img)
                        <img src="{{ \App\Services\ImageUploadService::url($img) }}" class="product-thumb" alt="">
                    @else
                        <div class="product-thumb-placeholder"><i class="fas fa-box"></i></div>
                    @endif
                    @endforeach
                    @if($order->items->count() > 4)
                        <div class="more-badge">+{{ $order->items->count()-4 }}</div>
                    @endif
                    <div class="ms-2">
                        <div style="font-size:.88rem;font-weight:600;color:#333;">
                            {{ Str::limit($order->items->first()->product->name ?? 'Sản phẩm', 35) }}
                        </div>
                        <div style="font-size:.8rem;color:#999;">
                            {{ $order->items->count() }} sản phẩm
                            @if($order->shipping_address)
                                · <i class="fas fa-map-marker-alt"></i> {{ Str::limit($order->shipping_address, 30) }}
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Total + action --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="order-total">{{ number_format($order->total_price,0,',','.') }} ₫</div>
                        @if($order->paymentMethod)
                        <div style="font-size:.78rem;color:#aaa;">{{ $order->paymentMethod->name }}</div>
                        @endif
                    </div>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn-detail">Chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="order-card">
        <div class="empty-state">
            <i class="fas fa-shopping-bag d-block"></i>
            <h5>Chưa có đơn hàng nào</h5>
            <p class="text-muted">Hãy khám phá các sản phẩm thời trang của chúng tôi</p>
            <a href="{{ route('products.index') }}" class="btn btn-dark px-4 rounded-pill mt-2">Mua sắm ngay</a>
        </div>
    </div>
    @endforelse

    <div class="d-flex justify-content-center mt-3">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</div>
</div>
@endsection
