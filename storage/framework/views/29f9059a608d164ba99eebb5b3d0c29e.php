

<?php $__env->startSection('title', 'Quản Lý Section: ' . $productSection->name); ?>
<?php $__env->startSection('page_title', 'Quản Lý Section: ' . $productSection->name); ?>
<?php $__env->startSection('header_icon', 'fas fa-edit'); ?>

<?php $__env->startSection('extra_css'); ?>
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
    .product-item {
        cursor: move;
        transition: all 0.3s ease;
    }
    .product-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    .product-item.sortable-ghost {
        opacity: 0.4;
        background: #e9ecef;
    }
    .available-products .product-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .available-products .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .available-products .product-card.selected {
        border: 2px solid #8B3A3A;
        background: #fff5f5;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Section Info -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông Tin Section</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.product-sections.update', $productSection)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Section *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo e($productSection->name); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug</label>
                            <input type="text" name="slug" class="form-control" value="<?php echo e($productSection->slug); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô Tả</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo e($productSection->description); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số Sản Phẩm Tối Đa *</label>
                            <input type="number" name="max_products" class="form-control" value="<?php echo e($productSection->max_products); ?>" min="1" max="50" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thứ Tự Hiển Thị *</label>
                            <input type="number" name="display_order" class="form-control" value="<?php echo e($productSection->display_order); ?>" min="0" required>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?php echo e($productSection->is_active ? 'checked' : ''); ?>>
                                <label class="form-check-label fw-bold" for="is_active">Hiển thị</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Cập Nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Management -->
        <div class="col-lg-8">
            <!-- Selected Products -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle"></i> Sản Phẩm Đã Chọn 
                        <span class="badge bg-light text-dark" id="selectedCount"><?php echo e($sectionProducts->count()); ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.product-sections.manage-products', $productSection)); ?>" method="POST" id="productForm">
                        <?php echo csrf_field(); ?>
                        <div id="selectedProducts" class="mb-3">
                            <?php $__empty_1 = true; $__currentLoopData = $sectionProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="product-item border rounded p-3 mb-2 d-flex align-items-center" data-id="<?php echo e($product->id); ?>">
                                    <div class="drag-handle me-3">
                                        <i class="fas fa-grip-vertical text-muted"></i>
                                    </div>
                                    <?php if($product->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                                             alt="<?php echo e($product->name); ?>" 
                                             style="width: 60px; height: 60px; object-fit: cover;" 
                                             class="rounded me-3">
                                    <?php elseif($product->images->first() && $product->images->first()->image_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" 
                                             alt="<?php echo e($product->name); ?>" 
                                             style="width: 60px; height: 60px; object-fit: cover;" 
                                             class="rounded me-3">
                                    <?php else: ?>
                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <strong><?php echo e($product->name); ?></strong>
                                        <br><small class="text-muted"><?php echo e(number_format($product->price)); ?>đ</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger remove-product">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <input type="hidden" name="products[]" value="<?php echo e($product->id); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-muted text-center py-4" id="emptyMessage">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Chưa có sản phẩm nào. Chọn sản phẩm bên dưới để thêm vào.
                                </p>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Lưu Danh Sách Sản Phẩm
                        </button>
                    </form>
                </div>
            </div>

            <!-- Available Products -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Tất Cả Sản Phẩm</h5>
                </div>
                <div class="card-body">
                    <input type="text" id="searchProduct" class="form-control mb-3" placeholder="Tìm kiếm sản phẩm...">
                    <div class="row available-products" id="availableProducts">
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-3 product-search-item" data-name="<?php echo e(strtolower($product->name)); ?>">
                                <div class="product-card border rounded p-3 <?php echo e($sectionProducts->contains($product->id) ? 'selected' : ''); ?>" 
                                     data-id="<?php echo e($product->id); ?>"
                                     onclick="toggleProduct(this)">
                                    <div class="d-flex align-items-center">
                                        <?php if($product->image): ?>
                                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                                                 alt="<?php echo e($product->name); ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                 class="rounded me-2">
                                        <?php elseif($product->images->first() && $product->images->first()->image_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" 
                                                 alt="<?php echo e($product->name); ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                 class="rounded me-2">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <strong class="d-block"><?php echo e($product->name); ?></strong>
                                            <small class="text-muted"><?php echo e(number_format($product->price)); ?>đ</small>
                                        </div>
                                        <i class="fas fa-check-circle text-success" style="display: <?php echo e($sectionProducts->contains($product->id) ? 'block' : 'none'); ?>;"></i>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_js'); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Initialize Sortable
const selectedProducts = document.getElementById('selectedProducts');
if (selectedProducts) {
    new Sortable(selectedProducts, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost'
    });
}

// Toggle product selection
function toggleProduct(card) {
    const productId = card.dataset.id;
    const isSelected = card.classList.contains('selected');
    
    if (isSelected) {
        // Remove from selected
        card.classList.remove('selected');
        card.querySelector('.fa-check-circle').style.display = 'none';
        document.querySelector(`#selectedProducts .product-item[data-id="${productId}"]`)?.remove();
    } else {
        // Add to selected
        card.classList.add('selected');
        card.querySelector('.fa-check-circle').style.display = 'block';
        addProductToSelected(card);
    }
    
    updateSelectedCount();
    toggleEmptyMessage();
}

// Add product to selected list
function addProductToSelected(card) {
    const productId = card.dataset.id;
    const productName = card.querySelector('strong').textContent;
    const productPrice = card.querySelector('.text-muted').textContent;
    const productImg = card.querySelector('img');
    
    const emptyMsg = document.getElementById('emptyMessage');
    if (emptyMsg) emptyMsg.remove();
    
    const productItem = document.createElement('div');
    productItem.className = 'product-item border rounded p-3 mb-2 d-flex align-items-center';
    productItem.dataset.id = productId;
    
    let imgHtml = '';
    if (productImg) {
        imgHtml = `<img src="${productImg.src}" alt="${productName}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded me-3">`;
    } else {
        imgHtml = '<div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="fas fa-image text-white"></i></div>';
    }
    
    productItem.innerHTML = `
        <div class="drag-handle me-3">
            <i class="fas fa-grip-vertical text-muted"></i>
        </div>
        ${imgHtml}
        <div class="flex-grow-1">
            <strong>${productName}</strong>
            <br><small class="text-muted">${productPrice}</small>
        </div>
        <button type="button" class="btn btn-sm btn-danger remove-product">
            <i class="fas fa-times"></i>
        </button>
        <input type="hidden" name="products[]" value="${productId}">
    `;
    
    selectedProducts.appendChild(productItem);
    
    // Add remove handler
    productItem.querySelector('.remove-product').addEventListener('click', function() {
        productItem.remove();
        card.classList.remove('selected');
        card.querySelector('.fa-check-circle').style.display = 'none';
        updateSelectedCount();
        toggleEmptyMessage();
    });
}

// Remove product handlers
document.querySelectorAll('.remove-product').forEach(btn => {
    btn.addEventListener('click', function() {
        const productItem = this.closest('.product-item');
        const productId = productItem.dataset.id;
        productItem.remove();
        
        const card = document.querySelector(`.available-products .product-card[data-id="${productId}"]`);
        if (card) {
            card.classList.remove('selected');
            card.querySelector('.fa-check-circle').style.display = 'none';
        }
        
        updateSelectedCount();
        toggleEmptyMessage();
    });
});

// Update selected count
function updateSelectedCount() {
    const count = document.querySelectorAll('#selectedProducts .product-item').length;
    document.getElementById('selectedCount').textContent = count;
}

// Toggle empty message
function toggleEmptyMessage() {
    const count = document.querySelectorAll('#selectedProducts .product-item').length;
    const emptyMsg = document.getElementById('emptyMessage');
    
    if (count === 0 && !emptyMsg) {
        const msg = document.createElement('p');
        msg.id = 'emptyMessage';
        msg.className = 'text-muted text-center py-4';
        msg.innerHTML = '<i class="fas fa-inbox fa-2x mb-2"></i><br>Chưa có sản phẩm nào. Chọn sản phẩm bên dưới để thêm vào.';
        selectedProducts.appendChild(msg);
    } else if (count > 0 && emptyMsg) {
        emptyMsg.remove();
    }
}

// Search products
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.product-search-item').forEach(item => {
        const name = item.dataset.name;
        item.style.display = name.includes(search) ? 'block' : 'none';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/product-sections/edit.blade.php ENDPATH**/ ?>