

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1 class="mb-5"><i class="fas fa-truck"></i> Thông Tin Giao Hàng</h1>
    
    <?php 
        $total = $carts->sum(function($item) {
            $price = $item->variant ? $item->variant->final_price : $item->product->price;
            return $price * $item->quantity;
        }); 
    ?>
    
    <form method="POST" action="<?php echo e(route('orders.store')); ?>" class="needs-validation" novalidate>
        <?php echo csrf_field(); ?>
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
                                <select name="province" id="province" class="form-select form-select-lg <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                </select>
                                <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phường/Xã <span class="text-danger">*</span></label>
                                <select name="ward" id="ward" class="form-select form-select-lg <?php $__errorArgs = ['ward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required disabled>
                                    <option value="">-- Chọn Phường/Xã --</option>
                                </select>
                                <?php $__errorArgs = ['ward'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                            <input type="text" name="address_detail" class="form-control form-control-lg <?php $__errorArgs = ['address_detail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Số nhà, tên đường..." required value="<?php echo e(old('address_detail')); ?>">
                            <input type="hidden" name="shipping_address" id="shipping_address">
                            <?php $__errorArgs = ['address_detail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control form-control-lg <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="0123456789" pattern="[0-9]{10,11}" required value="<?php echo e(old('phone')); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <?php
                            // Only show COD and ATM payment methods
                            $paymentMethods = \App\Models\PaymentMethod::active()
                                ->whereIn('code', ['cod', 'atm'])
                                ->ordered()
                                ->get();
                        ?>
                        
                        <?php if($paymentMethods->count() > 0): ?>
                            <div class="payment-methods">
                                <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="payment-method-item mb-3">
                                    <input type="radio" name="payment_method_id" id="payment_<?php echo e($method->id); ?>" 
                                           value="<?php echo e($method->id); ?>" class="payment-radio" 
                                           <?php echo e(old('payment_method_id') == $method->id ? 'checked' : ($loop->first ? 'checked' : '')); ?> required>
                                    <label for="payment_<?php echo e($method->id); ?>" class="payment-label">
                                        <div class="d-flex align-items-center">
                                            <?php if($method->logo): ?>
                                                <img src="<?php echo e(asset('storage/' . $method->logo)); ?>" alt="<?php echo e($method->name); ?>" class="payment-logo me-3">
                                            <?php else: ?>
                                                <div class="payment-icon me-3">
                                                    <i class="fas fa-<?php echo e($method->code == 'cod' ? 'money-bill-wave' : 'credit-card'); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold"><?php echo e($method->name); ?></div>
                                                <?php if($method->description): ?>
                                                    <small class="text-muted"><?php echo e($method->description); ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="check-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            
                            <div class="alert alert-light border mt-3 mb-0">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Bạn có thể chọn thanh toán khi nhận hàng hoặc thanh toán trực tuyến qua thẻ ATM
                                </small>
                            </div>
                            
                            <?php $__errorArgs = ['payment_method_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> Chưa có phương thức thanh toán nào được kích hoạt. Vui lòng liên hệ quản trị viên.
                            </div>
                        <?php endif; ?>
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
                            <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong class="text-truncate flex-grow-1"><?php echo e(Str::limit($item->product->name, 25)); ?></strong>
                                    <small class="text-muted ms-2">x<?php echo e($item->quantity); ?></small>
                                </div>
                                
                                <?php if($item->variant): ?>
                                    <div class="mb-2">
                                        <?php if($item->variant->size): ?>
                                            <span class="badge bg-light text-dark border me-1" style="font-size: 0.7rem;">
                                                <i class="fas fa-ruler-combined"></i> <?php echo e($item->variant->size); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php if($item->variant->color): ?>
                                            <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">
                                                <i class="fas fa-palette"></i> <?php echo e(ucfirst($item->variant->color)); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php
                                        $itemPrice = $item->variant ? $item->variant->final_price : $item->product->price;
                                    ?>
                                    <small class="text-muted"><?php echo e(number_format($itemPrice,0,',','.')); ?> ₫</small>
                                    <strong class="text-primary"><?php echo e(number_format($itemPrice * $item->quantity,0,',','.')); ?> ₫</strong>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền hàng:</span>
                            <span><?php echo e(number_format($total, 0, ',', '.')); ?> ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted small">
                            <span>Phí giao hàng:</span>
                            <span>0 ₫</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between fw-bold" style="font-size: 1.4rem;">
                            <span>Tổng:</span>
                            <span class="text-danger"><?php echo e(number_format($total, 0, ',', '.')); ?> ₫</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white p-4 border-top">
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold mb-2" style="border-radius: 8px;">
                            <i class="fas fa-check-circle"></i> Xác Nhận Đặt Hàng
                        </button>
                        <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-secondary w-100">
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/orders/create.blade.php ENDPATH**/ ?>