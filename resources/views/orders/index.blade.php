@extends('layouts.app')

@section('content')
<style>
.oh-page { background:#f9f9f7; min-height:100vh; padding:40px 0; }

/* Stat cards */
.stat-card {
    background:#fff;
    border-radius:12px;
    padding:20px 24px 16px;
    border:1px solid #efefef;
    box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.stat-card .sc-label { font-size:.82rem; color:#888; margin-bottom:8px; font-weight:500; }
.stat-card .sc-num  { font-size:1.8rem; font-weight:700; line-height:1; margin-bottom:12px; }
.stat-card .sc-line { height:3px; width:36px; background:#e8c97a; border-radius:2px; }

/* Filter tabs */
.filter-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; }
.filter-tab {
    padding:6px 16px; border-radius:50px; font-size:.83rem; font-weight:500;
    border:1.5px solid #e0e0e0; background:#fff; color:#666;
    text-decoration:none; transition:all .18s;
}
.filter-tab:hover  { border-color:#8B3A3A; color:#8B3A3A; }
.filter-tab.active { background:#8B3A3A; border-color:#8B3A3A; color:#fff; }

/* Order card */
.order-card {
    background:#fff; border-radius:12px; border:1px solid #efefef;
    box-shadow:0 1px 4px rgba(0,0,0,.04); margin-bottom:12px; overflow:hidden;
    transition:box-shadow .18s;
}
.order-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }
.order-card-head {
    padding:14px 20px; border-bottom:1px solid #f5f5f5;
    display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;
}
.order-card-body { padding:16px 20px; }

.status-pill {
    padding:4px 12px; border-radius:50px; font-size:.75rem; font-weight:600;
}
.sp-pending    { background:#fff8e1; color:#b45309; }
.sp-processing { background:#e3f2fd; color:#1565c0; }
.sp-shipping   { background:#e0f7fa; color:#00695c; }
.sp-delivered  { background:#e8f5e9; color:#2e7d32; }
.sp-cancelled  { background:#fce4ec; color:#c62828; }

.prod-thumb {
    width:56px; height:56px; border-radius:8px; object-fit:cover; border:1px solid #f0f0f0;
}
.prod-thumb-ph {
    width:56px; height:56px; border-radius:8px; background:#f5f5f5;
    display:flex; align-items:center; justify-content:center; color:#ccc; font-size:1.2rem;
}
.more-pill {
    width:56px; height:56px; border-radius:8px; background:#f5f5f5;
    display:flex; align-items:center; justify-content:center; font-size:.78rem; color:#888; font-weight:600;
}
.btn-view {
    padding:6px 18px; border-radius:50px; font-size:.82rem; font-weight:600;
    border:1.5px solid #8B3A3A; color:#8B3A3A; background:#fff;
    text-decoration:none; transition:all .18s; white-space:nowrap;
}
.btn-view:hover { background:#8B3A3A; color:#fff; }
</style>

<div class="oh-page">
<div class="container" style="max-width:860px;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color:#1a1a1a;">Lịch sử mua hàng</h4>
            <p class="text-muted mb-0" style="font-size:.85rem;">Theo dõi và quản lý đơn hàng của bạn</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-view">+ Mua thêm</a>
    </div>

    {{-- Stats --}}
    @php
        $delivered = $orders->where('status','delivered');
        $processing = $orders->whereIn('status',['pending','processing','shipping']);
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="sc-label">Tổng đơn hàng</div>
                <div class="sc-num" style="color:#555;">{{ $orders->total() }}</div>
                <div class="sc-line"></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="sc-label">Đang xử lý</div>
                <div class="sc-num" style="color:#1565c0;">{{ $processing->count() }}</div>
                <div class="sc-line"></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="sc-label">Đã giao</div>
                <div class="sc-num" style="color:#2e7d32;">{{ $delivered->count() }}</div>
                <div class="sc-line"></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="sc-label">Tổng chi tiêu</div>
                <div class="sc-num" style="color:#8B3A3A;font-size:1.4rem;">{{ number_format($orders->sum('total_price'),0,',','.')}}₫</div>
                <div class="sc-line"></div>
                <div style="font-size:.75rem;color:#bbb;margin-top:6px;">Tất cả đơn hàng</div>
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
        $spClass = match($order->status) {
            'pending'    => 'sp-pending',
            'processing' => 'sp-processing',
            'shipping'   => 'sp-shipping',
            'delivered'  => 'sp-delivered',
            'cancelled'  => 'sp-cancelled',
            default      => 'sp-pending',
        };
    @endphp
    <div class="order-card">
        <div class="order-card-head">
            <div class="d-flex align-items-center gap-3">
                <span style="font-weight:700;color:#222;font-size:.92rem;">Đơn #{{ $order->id }}</span>
                <span class="text-muted" style="font-size:.8rem;">{{ $order->created_at->format('d/m/Y · H:i') }}</span>
            </div>
            <span class="status-pill {{ $spClass }}">{{ $order->status_label }}</span>
        </div>
        <div class="order-card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @foreach($order->items->take(4) as $item)
                    @php $img = $item->product->image ?? ($item->product->images->first()->path ?? null); @endphp
                    @if($img)
                        <img src="{{ \App\Services\ImageUploadService::url($img) }}" class="prod-thumb" alt="">
                    @else
                        <div class="prod-thumb-ph"><i class="fas fa-box"></i></div>
                    @endif
                    @endforeach
                    @if($order->items->count() > 4)
                        <div class="more-pill">+{{ $order->items->count()-4 }}</div>
                    @endif
                    <div class="ms-1">
                        <div style="font-size:.87rem;font-weight:600;color:#333;">
                            {{ Str::limit($order->items->first()->product->name ?? 'Sản phẩm', 38) }}
                        </div>
                        <div style="font-size:.78rem;color:#aaa;">
                            {{ $order->items->count() }} sản phẩm
                            @if($order->shipping_address)
                                &nbsp;·&nbsp;<i class="fas fa-map-marker-alt"></i> {{ Str::limit($order->shipping_address, 28) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div style="font-size:1.05rem;font-weight:700;color:#8B3A3A;">{{ number_format($order->total_price,0,',','.')}} ₫</div>
                        @if($order->paymentMethod)
                        <div style="font-size:.75rem;color:#bbb;">{{ $order->paymentMethod->name }}</div>
                        @endif
                    </div>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn-view">Chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="order-card text-center py-5">
        <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>
        <p class="text-muted mb-3">Chưa có đơn hàng nào</p>
        <a href="{{ route('products.index') }}" class="btn-view">Mua sắm ngay</a>
    </div>
    @endforelse

    <div class="d-flex justify-content-center mt-3">
        {{ $orders->appends(request()->query())->links() }}
    </div>

</div>
</div>
@endsection
