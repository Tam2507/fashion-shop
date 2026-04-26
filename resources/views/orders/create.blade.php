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
        <input type="hidden" name="selected_items" value="{{ $cartIds ?? '' }}">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Shipping Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng</h5>
                    </div>
                    <div class="card-body p-4">
                        @auth
                        @php $user = auth()->user(); @endphp
                        @if($user->address || $user->phone)
                        <div class="alert alert-success d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Địa chỉ đã lưu:</strong> {{ $user->address ?? 'Chưa có' }}
                                @if($user->phone) — {{ $user->phone }} @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success" id="toggleChangeAddress">
                                <i class="fas fa-edit"></i> Thay đổi
                            </button>
                        </div>

                        {{-- Ẩn/hiện form thay đổi địa chỉ --}}
                        <div id="addressForm" style="display:none;">
                        @else
                        <div id="addressForm">
                        @endif
                        @endauth
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
                            <input type="text" name="address_detail" id="address_detail" class="form-control form-control-lg @error('address_detail') is-invalid @enderror" placeholder="Số nhà, tên đường..." value="{{ old('address_detail') }}">
                            @error('address_detail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone_new" id="phone_new" class="form-control form-control-lg @error('phone') is-invalid @enderror" placeholder="0123456789" pattern="[0-9]{10,11}" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>{{-- end #addressForm --}}

                        {{-- Hidden inputs gửi lên server --}}
                        <input type="hidden" name="shipping_address" id="shipping_address">
                        @auth
                        <input type="hidden" name="phone" id="phone_hidden" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                        <input type="hidden" name="use_saved_address" id="use_saved_address" value="{{ (auth()->user()->address || auth()->user()->phone) ? '1' : '0' }}">
                        @else
                        <input type="hidden" name="phone" id="phone_hidden" value="{{ old('phone') }}">
                        <input type="hidden" name="use_saved_address" id="use_saved_address" value="0">
                        @endauth

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
                                                <img src="{{ \App\Services\ImageUploadService::url($method->logo) }}" alt="{{ $method->name }}" class="payment-logo me-3">
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

                            {{-- SePay option --}}
                            <div class="payment-method-item mb-3 mt-3">
                                <input type="radio" name="payment_method_id" id="payment_sepay" 
                                       value="sepay" class="payment-radio">
                                <label for="payment_sepay" class="payment-label">
                                    <div class="d-flex align-items-center">
                                        <div class="payment-icon me-3" style="background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%);">
                                            <i class="fas fa-qrcode"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">Thanh toán qua SePay</div>
                                            <small class="text-muted">Chuyển khoản ngân hàng / QR Code</small>
                                        </div>
                                        <div class="check-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
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

                        {{-- Mã giảm giá --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" id="couponInput" class="form-control" placeholder="Nhập mã..." style="text-transform:uppercase;">
                                <button type="button" class="btn btn-outline-danger d-none" id="removeCouponBtn" onclick="removeCoupon()" title="Hủy mã">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary fw-bold" onclick="applyCoupon()">Áp dụng</button>
                            </div>
                            <div id="couponMsg" class="small mt-1"></div>
                        </div>
                        <input type="hidden" name="coupon_code" id="couponCode" value="">

                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền hàng:</span>
                            <span>{{ number_format($total, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success" id="discountRow" style="display:none!important;">
                            <span>Giảm giá:</span>
                            <span id="discountAmount">-0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted small">
                            <span>Phí giao hàng:</span>
                            <span id="shippingFee">30.000 ₫</span>
                        </div>
                        <input type="hidden" name="shipping_fee" id="shippingFeeInput" value="30000">
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between fw-bold" style="font-size: 1.4rem;">
                            <span>Tổng:</span>
                            <span class="text-danger" id="finalTotal">{{ number_format($total + 30000, 0, ',', '.') }} ₫</span>
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
    const API_BASE = 'https://provinces.open-api.vn/api/v2';
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');
    const addressDetailInput = document.getElementById('address_detail');
    const shippingAddressInput = document.getElementById('shipping_address');
    const phoneHidden = document.getElementById('phone_hidden');
    const phoneNew = document.getElementById('phone_new');
    const useSavedInput = document.getElementById('use_saved_address');
    const addressForm = document.getElementById('addressForm');
    const toggleBtn = document.getElementById('toggleChangeAddress');

    // Toggle form thay đổi địa chỉ
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isHidden = addressForm.style.display === 'none';
            addressForm.style.display = isHidden ? 'block' : 'none';
            useSavedInput.value = isHidden ? '0' : '1';
            this.innerHTML = isHidden
                ? '<i class="fas fa-times"></i> Hủy'
                : '<i class="fas fa-edit"></i> Thay đổi';
        });
    }

    // Load provinces
    async function loadProvinces() {
        try {
            const res = await fetch(`${API_BASE}/p/`);
            const data = await res.json();
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.code;
                opt.textContent = p.name;
                provinceSelect.appendChild(opt);
            });
        } catch (e) {
            console.error('Lỗi tải tỉnh/thành:', e);
        }
    }

    provinceSelect.addEventListener('change', async function() {
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
        wardSelect.disabled = true;
        if (!this.value) return;
        try {
            const res = await fetch(`${API_BASE}/p/${this.value}?depth=2`);
            const data = await res.json();
            if (data.wards?.length) {
                data.wards.forEach(w => {
                    const opt = document.createElement('option');
                    opt.value = w.code;
                    opt.textContent = w.name;
                    wardSelect.appendChild(opt);
                });
                wardSelect.disabled = false;
            }
        } catch (e) {
            console.error('Lỗi tải phường/xã:', e);
        }
    });

    loadProvinces();

    // Submit handler
    document.querySelector('.needs-validation').addEventListener('submit', function(event) {
        const useSaved = useSavedInput.value === '1';

        if (useSaved) {
            // Dùng địa chỉ đã lưu từ profile
            shippingAddressInput.value = '{{ addslashes(auth()->user()->address ?? '') }}';
            phoneHidden.value = '{{ auth()->user()->phone ?? '' }}';
        } else {
            // Dùng địa chỉ nhập mới
            const provinceName = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
            const wardName = wardSelect.options[wardSelect.selectedIndex]?.text || '';
            const detail = addressDetailInput?.value.trim() || '';
            const full = [detail, wardName, provinceName].filter(p => p && !p.startsWith('--')).join(', ');
            shippingAddressInput.value = full;
            phoneHidden.value = phoneNew?.value || '';

            // Validate form khi nhập mới
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
        }

        this.classList.add('was-validated');
    }, false);

    // Coupon
    const originalTotal = {{ $total }};
    const SHIPPING_FEE = 30000;
    let discountValue = 0;
    let freeShipping = false;

    // Hiển thị tổng ban đầu có phí ship
    document.getElementById('finalTotal').textContent = formatMoney(originalTotal + SHIPPING_FEE) + ' ₫';

    function removeCoupon() {
        discountValue = 0;
        freeShipping = false;
        document.getElementById('couponInput').value = '';
        document.getElementById('couponCode').value = '';
        document.getElementById('couponMsg').innerHTML = '';
        document.getElementById('discountRow').style.display = 'none';
        document.getElementById('removeCouponBtn').classList.add('d-none');
        document.getElementById('shippingFee').textContent = formatMoney(SHIPPING_FEE) + ' ₫';
        document.getElementById('shippingFeeInput').value = SHIPPING_FEE;
        document.getElementById('finalTotal').textContent = formatMoney(originalTotal + SHIPPING_FEE) + ' ₫';
    }

    async function applyCoupon() {
        const code = document.getElementById('couponInput').value.trim().toUpperCase();
        const msg = document.getElementById('couponMsg');
        if (!code) { msg.innerHTML = '<span class="text-danger">Vui lòng nhập mã.</span>'; return; }

        try {
            const res = await fetch('{{ route("coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code, total: originalTotal })
            });
            const data = await res.json();
            if (data.success) {
                discountValue = data.discount;
                freeShipping = data.free_shipping || false;
                document.getElementById('couponCode').value = code;
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('removeCouponBtn').classList.remove('d-none');
                if (freeShipping) {
                    document.getElementById('discountAmount').textContent = 'Miễn phí ship';
                    document.getElementById('shippingFee').textContent = '0 ₫';
                    document.getElementById('shippingFeeInput').value = 0;
                } else {
                    document.getElementById('discountAmount').textContent = '-' + formatMoney(discountValue) + ' ₫';
                    document.getElementById('shippingFee').textContent = formatMoney(SHIPPING_FEE) + ' ₫';
                    document.getElementById('shippingFeeInput').value = SHIPPING_FEE;
                }
                const shipping = freeShipping ? 0 : SHIPPING_FEE;
                document.getElementById('finalTotal').textContent = formatMoney(originalTotal - discountValue + shipping) + ' ₫';
                msg.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> ' + data.message + '</span>';
            } else {
                discountValue = 0;
                freeShipping = false;
                document.getElementById('couponCode').value = '';
                document.getElementById('discountRow').style.display = 'none';
                document.getElementById('removeCouponBtn').classList.add('d-none');
                document.getElementById('shippingFee').textContent = formatMoney(SHIPPING_FEE) + ' ₫';
                document.getElementById('shippingFeeInput').value = SHIPPING_FEE;
                document.getElementById('finalTotal').textContent = formatMoney(originalTotal + SHIPPING_FEE) + ' ₫';
                msg.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + data.message + '</span>';
            }
        } catch(e) {
            msg.innerHTML = '<span class="text-danger">Lỗi kết nối.</span>';
        }
    }

    function formatMoney(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
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