@extends('layouts.admin')

@section('title', 'Quản Lý Mã Giảm Giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản Lý Mã Giảm Giá</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Mã Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Loại</th>
                            <th>Giá Trị</th>
                            <th>Đơn Tối Thiểu</th>
                            <th>Giảm Tối Đa</th>
                            <th>Sử Dụng</th>
                            <th>Hiệu Lực</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $coupon->code }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $coupon->type_label }}</span>
                            </td>
                            <td>
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                @elseif($coupon->type === 'fixed_amount')
                                    {{ number_format($coupon->value, 0, ',', '.') }}đ
                                @else
                                    Miễn phí ship
                                @endif
                            </td>
                            <td>
                                {{ $coupon->minimum_amount ? number_format($coupon->minimum_amount, 0, ',', '.') . 'đ' : '-' }}
                            </td>
                            <td>
                                {{ $coupon->maximum_discount ? number_format($coupon->maximum_discount, 0, ',', '.') . 'đ' : '-' }}
                            </td>
                            <td>
                                {{ $coupon->used_count }}
                                @if($coupon->usage_limit)
                                    / {{ $coupon->usage_limit }}
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $coupon->starts_at->format('d/m/Y') }}<br>
                                    đến {{ $coupon->expires_at->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="{{ $coupon->id }}"
                                           {{ $coupon->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-info send-notification" 
                                            data-id="{{ $coupon->id }}"
                                            title="Gửi thông báo">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger delete-coupon" 
                                            data-id="{{ $coupon->id }}"
                                            title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $coupon->id }}" 
                                      action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                                <form id="notify-form-{{ $coupon->id }}" 
                                      action="{{ route('admin.coupons.send-notification', $coupon) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có mã giảm giá nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status
    document.querySelectorAll('.toggle-status').forEach(function(el) {
        el.addEventListener('change', function() {
            var couponId = this.dataset.id;
            var checkbox = this;
            fetch('/admin/coupons/' + couponId + '/toggle-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: '_token={{ csrf_token() }}'
            })
            .then(function(r){ return r.json(); })
            .then(function(data){ if (!data.success) checkbox.checked = !checkbox.checked; })
            .catch(function(){ checkbox.checked = !checkbox.checked; });
        });
    });

    // Send notification
    document.querySelectorAll('.send-notification').forEach(function(el) {
        el.addEventListener('click', function() {
            var couponId = this.dataset.id;
            if (confirm('Ban co chac muon gui thong bao ma giam gia nay den tat ca khach hang?')) {
                document.getElementById('notify-form-' + couponId).submit();
            }
        });
    });

    // Delete coupon
    document.querySelectorAll('.delete-coupon').forEach(function(el) {
        el.addEventListener('click', function() {
            var couponId = this.dataset.id;
            if (confirm('Ban co chac muon xoa ma giam gia nay?')) {
                document.getElementById('delete-form-' + couponId).submit();
            }
        });
    });
});
</script>
@endpush
@endsection
