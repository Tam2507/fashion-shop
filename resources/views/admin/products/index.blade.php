@extends('layouts.admin')

@section('page_title', 'Quản Lý Sản Phẩm')
@section('header_icon', 'fas fa-box')

@section('content')
<div class="container-fluid">
    <!-- Header with Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Quản Lý Sản Phẩm</h4>
            <p class="text-muted mb-0">Tổng cộng: <strong>{{ $products->total() }} sản phẩm</strong></p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Sắp hết</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>Lọc
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-4" id="bulkActions" style="display: none;">
        <div class="card-body py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span id="selectedCount">0</span> sản phẩm được chọn
                </div>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-success" onclick="bulkAction('activate')">
                        <i class="fas fa-check me-1"></i>Kích hoạt
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="bulkAction('deactivate')">
                        <i class="fas fa-pause me-1"></i>Tạm dừng
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash me-1"></i>Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Products Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($products->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll()">
                                </th>
                                <th style="width: 80px;">Ảnh</th>
                                <th>Sản Phẩm</th>
                                <th style="width: 120px;">Danh Mục</th>
                                <th style="width: 100px;">Giá</th>
                                <th style="width: 80px;">Tồn Kho</th>
                                <th style="width: 100px;">Trạng Thái</th>
                                <th style="width: 120px;">Ngày Tạo</th>
                                <th style="width: 120px;" class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}" onchange="updateBulkActions()">
                                </td>
                                <td>
                                    <div class="product-image">
                                        @php
                                            $displayImage = $product->image ?? $product->images->first()->path ?? null;
                                        @endphp
                                        @if($displayImage)
                                            <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $product->name }}" class="rounded">
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                        @if($product->variants->count() > 0)
                                            <div class="mt-1">
                                                <small class="badge bg-light text-dark">{{ $product->variants->count() }} biến thể</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                                    @if($product->variants->count() > 0)
                                        <br><small class="text-muted">Có biến thể</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $totalStock = $product->variants->count() > 0 ? $product->variants->sum('stock_quantity') : $product->quantity;
                                        $stockClass = $totalStock > 10 ? 'success' : ($totalStock > 0 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $stockClass }}">{{ $totalStock }}</span>
                                </td>
                                <td>
                                    @if($product->is_active ?? true)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Tạm dừng</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $product->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="Xem" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="d-inline" 
                                              onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm
                        </div>
                        <div>
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-box-open text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                    <h5 class="mb-2">Chưa có sản phẩm nào</h5>
                    <p class="text-muted mb-4">Hãy thêm sản phẩm đầu tiên để bắt đầu bán hàng</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm Đầu Tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.product-image {
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    color: #6c757d;
    font-size: 1.5rem;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-size: 0.75rem;
}
</style>

<script>
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checkboxes.length;
    } else {
        bulkActions.style.display = 'none';
    }
    
    // Update select all checkbox
    const allCheckboxes = document.querySelectorAll('.product-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.checked = checkboxes.length === allCheckboxes.length;
    selectAllCheckbox.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
}

function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const productIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (productIds.length === 0) {
        alert('Vui lòng chọn ít nhất một sản phẩm');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Kích hoạt ${productIds.length} sản phẩm đã chọn?`;
            break;
        case 'deactivate':
            confirmMessage = `Tạm dừng ${productIds.length} sản phẩm đã chọn?`;
            break;
        case 'delete':
            confirmMessage = `Xóa ${productIds.length} sản phẩm đã chọn? Hành động này không thể hoàn tác!`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.products.bulk-action") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        // Add product IDs
        productIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'product_ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function manageImages(productId) {
    const modal = new bootstrap.Modal(document.getElementById('imageManagementModal'));
    const content = document.getElementById('imageManagementContent');
    
    content.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>';
    modal.show();
    
    // Load image management content via AJAX
    fetch(`/admin/products/${productId}/images`)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra khi tải dữ liệu</div>';
        });
}

// Initialize bulk actions on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});
</script>
@endsection