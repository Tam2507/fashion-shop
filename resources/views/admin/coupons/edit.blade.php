@extends('layouts.admin')

@section('title', 'Sửa Mã Giảm Giá')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2>Sửa Mã Giảm Giá: {{ $coupon->code }}</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Mã Giảm Giá <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $coupon->code) }}"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Loại Giảm Giá <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Giảm theo %</option>
                                    <option value="free_shipping" {{ old('type', $coupon->type) === 'free_shipping' ? 'selected' : '' }}>Miễn phí vận chuyển</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Giá Trị <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('value') is-invalid @enderror" 
                                       id="value" 
                                       name="value" 
                                       value="{{ old('value', $coupon->value) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="minimum_amount" class="form-label">Đơn Hàng Tối Thiểu</label>
                                <input type="number" 
                                       class="form-control @error('minimum_amount') is-invalid @enderror" 
                                       id="minimum_amount" 
                                       name="minimum_amount" 
                                       value="{{ old('minimum_amount', $coupon->minimum_amount) }}"
                                       step="1000"
                                       min="0">
                                @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="maximum_discount" class="form-label">Giảm Tối Đa</label>
                                <input type="number" 
                                       class="form-control @error('maximum_discount') is-invalid @enderror" 
                                       id="maximum_discount" 
                                       name="maximum_discount" 
                                       value="{{ old('maximum_discount', $coupon->maximum_discount) }}"
                                       step="1000"
                                       min="0">
                                @error('maximum_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Giới Hạn Số Lần Sử Dụng</label>
                            <input type="number" 
                                   class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" 
                                   name="usage_limit" 
                                   value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                   min="1">
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Đã sử dụng: {{ $coupon->used_count }} lần</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control flatpickr @error('starts_at') is-invalid @enderror" 
                                       id="starts_at" 
                                       name="starts_at" 
                                       value="{{ old('starts_at', $coupon->starts_at->format('d/m/Y H:i')) }}"
                                       placeholder="dd/mm/yyyy HH:MM"
                                       required>
                                @error('starts_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expires_at" class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control flatpickr @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" 
                                       name="expires_at" 
                                       value="{{ old('expires_at', $coupon->expires_at->format('d/m/Y H:i')) }}"
                                       placeholder="dd/mm/yyyy HH:MM"
                                       required>
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập Nhật
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống Kê</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Đã sử dụng:</strong> {{ $coupon->used_count }} lần
                    </div>
                    <div class="mb-3">
                        <strong>Trạng thái:</strong> 
                        <span class="badge bg-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                            {{ $coupon->is_active ? 'Đang hoạt động' : 'Tạm dừng' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Còn hiệu lực:</strong> 
                        @if($coupon->expires_at > now())
                            <span class="text-success">{{ $coupon->expires_at->diffForHumans() }}</span>
                        @else
                            <span class="text-danger">Đã hết hạn</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr('.flatpickr', {
    enableTime: true,
    dateFormat: 'd/m/Y H:i',
    time_24hr: true,
    locale: { firstDayOfWeek: 1 }
});
</script>
@endpush
