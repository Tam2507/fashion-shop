@extends('layouts.admin')

@section('title', 'Tạo Mã Giảm Giá')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2>Tạo Mã Giảm Giá Mới</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Mã Giảm Giá <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}"
                                   placeholder="VD: SUMMER2024"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mã sẽ tự động chuyển thành chữ in hoa</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Loại Giảm Giá <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Giảm theo %</option>
                                    <option value="fixed_amount" {{ old('type') === 'fixed_amount' ? 'selected' : '' }}>Giảm cố định</option>
                                    <option value="free_shipping" {{ old('type') === 'free_shipping' ? 'selected' : '' }}>Miễn phí vận chuyển</option>
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
                                       value="{{ old('value') }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="value-hint">Nhập % hoặc số tiền</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="minimum_amount" class="form-label">Đơn Hàng Tối Thiểu</label>
                                <input type="number" 
                                       class="form-control @error('minimum_amount') is-invalid @enderror" 
                                       id="minimum_amount" 
                                       name="minimum_amount" 
                                       value="{{ old('minimum_amount') }}"
                                       step="1000"
                                       min="0">
                                @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Để trống nếu không giới hạn</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="maximum_discount" class="form-label">Giảm Tối Đa</label>
                                <input type="number" 
                                       class="form-control @error('maximum_discount') is-invalid @enderror" 
                                       id="maximum_discount" 
                                       name="maximum_discount" 
                                       value="{{ old('maximum_discount') }}"
                                       step="1000"
                                       min="0">
                                @error('maximum_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Chỉ áp dụng cho giảm theo %</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Giới Hạn Số Lần Sử Dụng</label>
                            <input type="number" 
                                   class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" 
                                   name="usage_limit" 
                                   value="{{ old('usage_limit') }}"
                                   min="1">
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để trống nếu không giới hạn</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control @error('starts_at') is-invalid @enderror" 
                                       id="starts_at" 
                                       name="starts_at" 
                                       value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('starts_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="expires_at" class="form-label">Ngày Kết Thúc <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" 
                                       name="expires_at" 
                                       value="{{ old('expires_at', now()->addDays(30)->format('Y-m-d\TH:i')) }}"
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
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt ngay
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="send_notification" 
                                       name="send_notification" 
                                       value="1"
                                       {{ old('send_notification') ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_notification">
                                    <strong>Gửi thông báo đến tất cả khách hàng</strong>
                                </label>
                                <small class="d-block text-muted">Email sẽ được gửi đến tất cả khách hàng đã đăng ký</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo Mã Giảm Giá
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
                    <h5 class="mb-0">Hướng Dẫn</h5>
                </div>
                <div class="card-body">
                    <h6>Loại giảm giá:</h6>
                    <ul>
                        <li><strong>Giảm theo %:</strong> Giảm theo phần trăm giá trị đơn hàng</li>
                        <li><strong>Giảm cố định:</strong> Giảm một số tiền cố định</li>
                        <li><strong>Miễn phí ship:</strong> Miễn phí vận chuyển</li>
                    </ul>

                    <h6 class="mt-3">Lưu ý:</h6>
                    <ul>
                        <li>Mã giảm giá phải là duy nhất</li>
                        <li>Giá trị giảm tối đa chỉ áp dụng cho giảm theo %</li>
                        <li>Có thể giới hạn số lần sử dụng</li>
                        <li>Gửi thông báo sẽ email đến tất cả khách hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#type').change(function() {
        const type = $(this).val();
        const valueHint = $('#value-hint');
        
        if (type === 'percentage') {
            valueHint.text('Nhập % (VD: 10 = giảm 10%)');
            $('#maximum_discount').prop('disabled', false);
        } else if (type === 'fixed_amount') {
            valueHint.text('Nhập số tiền (VD: 50000)');
            $('#maximum_discount').prop('disabled', true);
        } else {
            valueHint.text('Nhập 0 cho miễn phí ship');
            $('#maximum_discount').prop('disabled', true);
        }
    });
    
    // Trigger on load
    $('#type').trigger('change');
});
</script>
@endpush
@endsection
