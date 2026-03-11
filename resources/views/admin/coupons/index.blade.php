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
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').change(function() {
        const couponId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/admin/coupons/${couponId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Có lỗi xảy ra');
                $(this).prop('checked', !isChecked);
            }
        });
    });
    
    // Send notification
    $('.send-notification').click(function() {
        const couponId = $(this).data('id');
        
        if (confirm('Bạn có chắc muốn gửi thông báo mã giảm giá này đến tất cả khách hàng?')) {
            $('#notify-form-' + couponId).submit();
        }
    });
    
    // Delete coupon
    $('.delete-coupon').click(function() {
        const couponId = $(this).data('id');
        
        if (confirm('Bạn có chắc muốn xóa mã giảm giá này?')) {
            $('#delete-form-' + couponId).submit();
        }
    });
});
</script>
@endpush
@endsection
