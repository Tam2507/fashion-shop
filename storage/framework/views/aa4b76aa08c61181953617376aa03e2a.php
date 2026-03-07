

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1 class="mb-4"><i class="fas fa-shopping-cart"></i> Giỏ Hàng</h1>
    
    <?php if($carts->isEmpty()): ?>
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
            <p class="mt-3 mb-0">Giỏ hàng của bạn trống</p>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary mt-3"><i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách sản phẩm (<?php echo e($carts->count()); ?> mục)</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            <label class="form-check-label fw-bold" for="selectAll">
                                Chọn tất cả
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-bottom p-4 d-flex gap-3 align-items-start hover-shadow cart-item">
                        <div class="form-check mt-2">
                            <input class="form-check-input cart-checkbox" type="checkbox" value="<?php echo e($item->id); ?>" 
                                   id="cart_<?php echo e($item->id); ?>" onchange="updateTotal()">
                        </div>
                        <div style="width: 120px; height: 120px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                            <?php
                                $displayImage = $item->product->image ?? $item->product->images->first()->path ?? null;
                            ?>
                            <?php if($displayImage): ?>
                                <img src="/storage/<?php echo e($displayImage); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="<?php echo e($item->product->name); ?>" />
                            <?php else: ?>
                                <i class="fas fa-box text-muted" style="font-size: 2rem;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1"><?php echo e($item->product->name); ?></h5>
                            
                            <?php if($item->variant): ?>
                                <div class="mb-2">
                                    <?php if($item->variant->color): ?>
                                        <span class="text-muted small">
                                            <i class="fas fa-palette me-1"></i>Màu sắc: <strong><?php echo e(ucfirst($item->variant->color)); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                    <?php if($item->variant->size): ?>
                                        <span class="text-muted small ms-3">
                                            <i class="fas fa-ruler me-1"></i>Size: <strong><?php echo e($item->variant->size); ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="mb-2">
                                    <span class="badge bg-warning text-dark small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Chưa chọn màu sắc/size
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <p class="text-muted small mb-2">
                                <i class="fas fa-tag me-1"></i><?php echo e($item->product->category->name ?? 'Chưa phân loại'); ?>

                            </p>
                            
                            <?php if($item->variant): ?>
                                <p class="text-primary fw-bold mb-0" style="font-size: 1.1rem;">
                                    <?php echo e(number_format($item->variant->final_price, 0, ',', '.')); ?> ₫
                                </p>
                            <?php else: ?>
                                <p class="text-primary fw-bold mb-2" style="font-size: 1.1rem;">
                                    <?php echo e(number_format($item->product->price, 0, ',', '.')); ?> ₫
                                </p>
                            <?php endif; ?>
                            
                            <div class="d-flex align-items-center gap-2 mt-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0 small fw-bold">Số lượng:</label>
                                    <div class="input-group" style="width: 130px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(<?php echo e($item->id); ?>, -1, <?php echo e($item->variant ? $item->variant->stock_quantity : $item->product->quantity); ?>)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" id="qty_<?php echo e($item->id); ?>" value="<?php echo e($item->quantity); ?>" min="1" max="<?php echo e($item->variant ? $item->variant->stock_quantity : $item->product->quantity); ?>" class="form-control form-control-sm text-center" style="width: 60px;" readonly />
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(<?php echo e($item->id); ?>, 1, <?php echo e($item->variant ? $item->variant->stock_quantity : $item->product->quantity); ?>)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <form method="POST" action="<?php echo e(route('cart.remove', $item->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                            
                            <?php
                                $maxStock = $item->variant ? $item->variant->stock_quantity : $item->product->quantity;
                            ?>
                            <?php if($maxStock < 5): ?>
                                <p class="text-warning small mb-0 mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> Chỉ còn <?php echo e($maxStock); ?> sản phẩm
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="text-end text-nowrap">
                            <p class="small text-muted mb-1">Thành tiền</p>
                            <?php
                                $itemPrice = $item->variant ? $item->variant->final_price : $item->product->price;
                                $itemTotal = $itemPrice * $item->quantity;
                            ?>
                            <p class="fw-bold text-danger mb-0 item-total" style="font-size: 1.3rem;" data-price="<?php echo e($itemTotal); ?>">
                                <?php echo e(number_format($itemTotal, 0, ',', '.')); ?> ₫
                            </p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="card-footer bg-light p-3 text-end">
                    <form method="POST" action="<?php echo e(route('cart.clear')); ?>" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i> Xóa toàn bộ</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Tóm tắt đơn</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền hàng:</span>
                        <span id="displayTotal"><?php echo e(number_format($total,0,',','.')); ?> ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Phí giao hàng:</span>
                        <span>0 ₫</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-2" style="font-size: 1.3rem;">
                        <span>Tổng cộng:</span>
                        <span class="text-danger" id="displayGrandTotal"><?php echo e(number_format($total,0,',','.')); ?> ₫</span>
                    </div>
                    <p class="small text-muted mb-4" id="selectedCount">Đã chọn <?php echo e($carts->count()); ?> sản phẩm</p>
                    <form method="GET" action="<?php echo e(route('orders.create')); ?>" id="checkoutForm">
                        <input type="hidden" name="selected_items" id="selectedItems" value="">
                        <button type="submit" class="btn btn-primary w-100 py-3 mb-2 fw-bold" id="checkoutBtn">
                            <i class="fas fa-check-circle"></i> Tiến hành thanh toán
                        </button>
                    </form>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>

<script>
function updateQuantity(cartId, change, maxStock) {
    const qtyInput = document.getElementById(`qty_${cartId}`);
    const currentQty = parseInt(qtyInput.value);
    let newQty = currentQty + change;
    
    if (newQty < 1) newQty = 1;
    if (newQty > maxStock) {
        alert(`Số lượng tối đa: ${maxStock}`);
        return;
    }
    
    if (newQty === currentQty) return; // No change
    
    // Get the cart item element
    const cartItem = qtyInput.closest('.cart-item');
    const itemTotalEl = cartItem.querySelector('.item-total');
    const checkbox = cartItem.querySelector('.cart-checkbox');
    
    // Calculate unit price from current total and quantity
    const currentTotal = parseFloat(itemTotalEl.dataset.price);
    const unitPrice = currentTotal / currentQty;
    
    // Update via AJAX
    fetch(`/cart/update/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update quantity display
            qtyInput.value = newQty;
            
            // Calculate and update new total
            const newTotal = unitPrice * newQty;
            itemTotalEl.dataset.price = newTotal;
            itemTotalEl.textContent = newTotal.toLocaleString('vi-VN') + ' ₫';
            
            // Preserve checkbox state and update summary
            updateTotal();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Không thể cập nhật số lượng');
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.cart-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        saveCheckboxState(cb.value, cb.checked);
    });
    updateTotal();
}

function saveCheckboxState(itemId, checked) {
    const selectedItems = JSON.parse(localStorage.getItem('selectedCartItems') || '[]');
    if (checked) {
        if (!selectedItems.includes(itemId)) {
            selectedItems.push(itemId);
        }
    } else {
        const index = selectedItems.indexOf(itemId);
        if (index > -1) {
            selectedItems.splice(index, 1);
        }
    }
    localStorage.setItem('selectedCartItems', JSON.stringify(selectedItems));
}

function loadCheckboxStates() {
    const selectedItems = JSON.parse(localStorage.getItem('selectedCartItems') || '[]');
    const checkboxes = document.querySelectorAll('.cart-checkbox');
    
    // If no saved state, select all by default
    if (selectedItems.length === 0) {
        checkboxes.forEach(cb => {
            cb.checked = true;
            saveCheckboxState(cb.value, true);
        });
    } else {
        checkboxes.forEach(cb => {
            cb.checked = selectedItems.includes(cb.value);
        });
    }
}

function updateTotal() {
    const checkboxes = document.querySelectorAll('.cart-checkbox:checked');
    const selectedIds = [];
    let total = 0;
    
    checkboxes.forEach(cb => {
        selectedIds.push(cb.value);
        const cartItem = cb.closest('.cart-item');
        const itemTotal = parseFloat(cartItem.querySelector('.item-total').dataset.price);
        total += itemTotal;
    });
    
    // Update display
    document.getElementById('displayTotal').textContent = total.toLocaleString('vi-VN') + ' ₫';
    document.getElementById('displayGrandTotal').textContent = total.toLocaleString('vi-VN') + ' ₫';
    document.getElementById('selectedCount').textContent = `Đã chọn ${checkboxes.length} sản phẩm`;
    document.getElementById('selectedItems').value = selectedIds.join(',');
    
    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.cart-checkbox');
    const selectAll = document.getElementById('selectAll');
    selectAll.checked = checkboxes.length === allCheckboxes.length;
    selectAll.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
    
    // Enable/disable checkout button
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkboxes.length === 0) {
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> Vui lòng chọn sản phẩm';
    } else {
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = '<i class="fas fa-check-circle"></i> Tiến hành thanh toán';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCheckboxStates();
    updateTotal();
    
    // Add event listeners to checkboxes
    document.querySelectorAll('.cart-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            saveCheckboxState(this.value, this.checked);
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/cart/index.blade.php ENDPATH**/ ?>