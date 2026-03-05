@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5"><i class="fas fa-truck"></i> Thông Tin Giao Hàng</h1>
    
    @php 
        $total = $carts->sum(function($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        }); 
    @endphp
    
    <form method="POST" action="{{ route('orders.store') }}" class="needs-validation" novalidate>
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Shipping Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select name="province" id="province" class="form-select form-select-lg @error('province') is-invalid @enderror" required>
                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                </select>
                                @error('province')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phường/Xã <span class="text-danger">*</span></label>
                                <select name="ward" id="ward" class="form-select form-select-lg @error('ward') is-invalid @enderror" required disabled>
                                    <option value="">-- Chọn Phường/Xã --</option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                            <input type="text" name="address_detail" class="form-control form-control-lg @error('address_detail') is-invalid @enderror" placeholder="Số nhà, tên đường..." required value="{{ old('address_detail') }}">
                            <input type="hidden" name="shipping_address" id="shipping_address">
                            @error('address_detail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" placeholder="0123456789" pattern="[0-9]{10,11}" required value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Vui lòng kiểm tra kỹ thông tin giao hàng trước khi xác nhận.
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body p-4">
                        @php
                            // Only show COD and ATM payment methods
                            $paymentMethods = \App\Models\PaymentMethod::active()
                                ->whereIn('code', ['cod', 'atm'])
                                ->ordered()
                                ->get();
                        @endphp
                        
                        @if($paymentMethods->count() > 0)
                            <div class="payment-methods">
                                @foreach($paymentMethods as $method)
                                <div class="payment-method-item mb-3">
                                    <input type="radio" name="payment_method_id" id="payment_{{ $method->id }}" 
                                           value="{{ $method->id }}" class="payment-radio" 
                                           {{ old('payment_method_id') == $method->id ? 'checked' : ($loop->first ? 'checked' : '') }} required>
                                    <label for="payment_{{ $method->id }}" class="payment-label">
                                        <div class="d-flex align-items-center">
                                            @if($method->logo)
                                                <img src="{{ asset('storage/' . $method->logo) }}" alt="{{ $method->name }}" class="payment-logo me-3">
                                            @else
                                                <div class="payment-icon me-3">
                                                    <i class="fas fa-{{ $method->code == 'cod' ? 'money-bill-wave' : 'credit-card' }}"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ $method->name }}</div>
                                                @if($method->description)
                                                    <small class="text-muted">{{ $method->description }}</small>
                                                @endif
                                            </div>
                                            <div class="check-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="alert alert-light border mt-3 mb-0">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Bạn có thể chọn thanh toán khi nhận hàng hoặc thanh toán trực tuyến qua thẻ ATM
                                </small>
                            </div>
                            
                            @error('payment_method_id')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> Chưa có phương thức thanh toán nào được kích hoạt. Vui lòng liên hệ quản trị viên.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
                    <div class="card-header bg-success text-white py-4">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Tóm Tắt Đơn Hàng</h5>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 400px; overflow-y: auto;" class="mb-4">
                            @foreach($carts as $item)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong class="text-truncate flex-grow-1">{{ Str::limit($item->product->name, 25) }}</strong>
                                    <small class="text-muted ms-2">x{{ $item->quantity }}</small>
                                </div>
                                
                                @if($item->variant)
                                    <div class="mb-2">
                                        @if($item->variant->size)
                                            <span class="badge bg-light text-dark border me-1" style="font-size: 0.7rem;">
                                                <i class="fas fa-ruler-combined"></i> {{ $item->variant->size }}
                                            </span>
                                        @endif
                                        @if($item->variant->color)
                                            <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">
                                                <i class="fas fa-palette"></i> {{ ucfirst($item->variant->color) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    @php
                                        $itemPrice = $item->variant ? $item->variant->final_price : $item->product->price;
                                    @endphp
                                    <small class="text-muted">{{ number_format($itemPrice,0,',','.') }} ₫</small>
                                    <strong class="text-primary">{{ number_format($itemPrice * $item->quantity,0,',','.') }} ₫</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền hàng:</span>
                            <span>{{ number_format($total, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted small">
                            <span>Phí giao hàng:</span>
                            <span>0 ₫</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between fw-bold" style="font-size: 1.4rem;">
                            <span>Tổng:</span>
                            <span class="text-danger">{{ number_format($total, 0, ',', '.') }} ₫</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white p-4 border-top">
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mb-2" style="border-radius: 8px;">
                            <i class="fas fa-check-circle"></i> Xác Nhận Đặt Hàng
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Quay lại giỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Vietnam Provinces API Integration (v2 - after 07/2025 merge: provinces and wards only)
    const API_BASE = 'https://provinces.open-api.vn/api/v2';
    
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');
    const addressDetailInput = document.querySelector('input[name="address_detail"]');
    const shippingAddressInput = document.getElementById('shipping_address');
    
    let provincesData = [];
    
    // Fetch provinces on page load
    async function loadProvinces() {
        try {
            const response = await fetch(`${API_BASE}/p/`);
            provincesData = await response.json();
            
            provincesData.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading provinces:', error);
            alert('Không thể tải danh sách tỉnh/thành phố. Vui lòng thử lại.');
        }
    }
    
    // Load wards when province is selected (v2 has no districts, only wards)
    provinceSelect.addEventListener('change', async function() {
        const provinceCode = this.value;
        
        // Reset ward
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        wardSelect.disabled = true;
        
        if (!provinceCode) return;
        
        try {
            // Fetch specific province with depth=2 to get wards (no districts in v2)
            const response = await fetch(`${API_BASE}/p/${provinceCode}?depth=2`);
            const provinceData = await response.json();
            
            if (provinceData.wards && provinceData.wards.length > 0) {
                provinceData.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
                wardSelect.disabled = false;
            }
        } catch (error) {
            console.error('Error loading wards:', error);
            alert('Không thể tải danh sách phường/xã. Vui lòng thử lại.');
        }
    });
    
    // Load provinces on page load
    loadProvinces();
    
    // Bootstrap form validation with address combination
    (() => {
        'use strict';
        const form = document.querySelector('.needs-validation');
        
        form.addEventListener('submit', function(event) {
            // First, combine address fields
            const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
            const wardName = wardSelect.options[wardSelect.selectedIndex]?.text || '';
            const addressDetail = addressDetailInput.value.trim();
            
            // Combine into full address: "Số nhà, Phường/Xã, Tỉnh/Thành phố"
            const fullAddress = [addressDetail, wardName, provinceName]
                .filter(part => part && !part.startsWith('--'))
                .join(', ');
            
            shippingAddressInput.value = fullAddress;
            
            // Then check form validity
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    })();
</script>

<style>
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-method-item {
    position: relative;
}

.payment-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.payment-label {
    display: block;
    padding: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    margin-bottom: 0;
}

.payment-label:hover {
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.payment-radio:checked + .payment-label {
    border-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
}

.payment-logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 5px;
    background: white;
}

.payment-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    color: white;
    font-size: 1.5rem;
}

.check-icon {
    color: #e0e0e0;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.payment-radio:checked + .payment-label .check-icon {
    color: #667eea;
    transform: scale(1.2);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection