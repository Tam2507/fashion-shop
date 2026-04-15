@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width:860px;">

    {{-- Back + Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
        <div>
            <h5 class="fw-bold mb-0">Đơn hàng #{{ $order->id }}</h5>
            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
        </div>
        @php
            $spClass = match($order->status) {
                'pending'    => 'bg-warning text-dark',
                'processing' => 'bg-primary',
                'shipping'   => 'bg-info',
                'delivered'  => 'bg-success',
                'cancelled'  => 'bg-danger',
                default      => 'bg-secondary',
            };
        @endphp
        <span class="badge {{ $spClass }} ms-auto px-3 py-2" style="font-size:.82rem;">{{ $order->status_label }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Sản phẩm --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Sản phẩm đã đặt</h6>
                    @forelse($order->items as $item)
                    @php $img = $item->product ? ($item->product->image ?? ($item->product->images->first()->path ?? null)) : null; @endphp
                    <div class="d-flex gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        @if($img)
                            <img src="{{ \App\Services\ImageUploadService::url($img) }}"
                                 style="width:70px;height:70px;object-fit:cover;border-radius:10px;border:1px solid #f0f0f0;">
                        @else
                            <div style="width:70px;height:70px;background:#f5f5f5;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-box text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.92rem;">
                                {{ $item->product->name ?? 'Sản phẩm #'.$item->product_id }}
                            </div>
                            @if($item->variant)
                                <small class="text-muted">
                                    @if($item->variant->size) Size: {{ $item->variant->size }} @endif
                                    @if($item->variant->color) · Màu: {{ $item->variant->color }} @endif
                                </small>
                            @endif
                            <div class="text-muted small">x{{ $item->quantity }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</div>
                            <small class="text-muted">{{ number_format($item->price, 0, ',', '.') }}₫/cái</small>
                        </div>
                    </div>

                    {{-- Form đánh giá — chỉ hiện khi đã delivered --}}
                    @if($order->status === 'delivered' && $item->product)
                        @if(in_array($item->product->id, $reviewedProductIds))
                            <div class="d-flex align-items-center gap-2 mt-1 mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <small class="text-success fw-semibold">Đã đánh giá sản phẩm này</small>
                            </div>
                        @else
                            @php $autoOpen = session('show_review') && $loop->first; @endphp
                            <div class="mt-2 mb-1">
                                <button class="btn btn-sm btn-outline-warning rounded-pill"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#review-{{ $item->product->id }}">
                                    <i class="fas fa-star me-1"></i>Viết đánh giá
                                </button>
                                <div class="collapse mt-3 {{ $autoOpen ? 'show' : '' }}" id="review-{{ $item->product->id }}">
                                    <form method="POST" action="{{ route('reviews.store', $item->product->id) }}"
                                          class="p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold mb-1">Đánh giá của bạn</label>
                                            <div class="star-rating d-flex gap-1" data-product="{{ $item->product->id }}">
                                                @for($i=5;$i>=1;$i--)
                                                <input type="radio" name="rating" id="star-{{ $item->product->id }}-{{ $i }}" value="{{ $i }}" required class="d-none">
                                                <label for="star-{{ $item->product->id }}-{{ $i }}" class="star-label fs-4" style="cursor:pointer;color:#d1d5db;" title="{{ $i }} sao">★</label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <textarea name="comment" class="form-control form-control-sm" rows="2"
                                                      placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-warning rounded-pill px-3 fw-semibold">
                                            <i class="fas fa-paper-plane me-1"></i>Gửi đánh giá
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                    @empty
                        <p class="text-muted">Không có sản phẩm nào.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Thông tin đơn --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Thông tin đơn hàng</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Tổng tiền</span>
                        <span class="fw-bold text-danger">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Trạng thái</span>
                        <span class="badge {{ $spClass }}">{{ $order->status_label }}</span>
                    </div>
                    @if($order->shipping_address)
                    <div class="mb-2">
                        <span class="text-muted small d-block">Địa chỉ giao hàng</span>
                        <span style="font-size:.88rem;">{{ $order->shipping_address }}</span>
                    </div>
                    @endif
                    @if($order->phone)
                    <div class="mb-2">
                        <span class="text-muted small">SĐT: </span>
                        <span style="font-size:.88rem;">{{ $order->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Nút xác nhận đã nhận hàng --}}
            @if($order->status === 'shipping')
            <div class="card border-0 shadow-sm rounded-3" style="border-left:4px solid #2e7d32 !important;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-box-open fa-2x text-success mb-2"></i>
                    <p class="small text-muted mb-3">Bạn đã nhận được hàng chưa? Xác nhận để hoàn tất đơn hàng và có thể đánh giá sản phẩm.</p>
                    <form method="POST" action="{{ route('orders.confirm-received', $order->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold"
                                onclick="return confirm('Xác nhận bạn đã nhận được hàng?')">
                            <i class="fas fa-check me-2"></i>Đã nhận được hàng
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($order->status === 'delivered')
            <div class="card border-0 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0 !important;">
                <div class="card-body p-3 text-center">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small class="text-success fw-semibold">Đơn hàng đã hoàn tất</small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.star-rating { flex-direction: row-reverse; }
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label { color: #f59e0b !important; }
</style>
@endpush

@push('scripts')
<script>
@if(session('show_review'))
    document.addEventListener('DOMContentLoaded', function() {
        const firstReview = document.querySelector('.star-rating');
        if (firstReview) {
            firstReview.closest('.card').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
@endif
</script>
@endpush
