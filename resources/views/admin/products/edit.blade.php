@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Chỉnh Sửa Sản Phẩm: {{ $product->name }}
                </h3>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay Lại
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                <i class="fas fa-info-circle me-2"></i>Thông Tin Cơ Bản
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button">
                <i class="fas fa-images me-2"></i>Quản Lý Ảnh
                <span class="badge bg-primary ms-2">{{ $product->images->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button">
                <i class="fas fa-th me-2"></i>Variants
                <span class="badge bg-success ms-2">{{ $product->variants->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="productTabContent">
        <!-- Tab 1: Thông Tin Cơ Bản -->
        <div class="tab-pane fade show active" id="info" role="tabpanel">
            @include('admin.products.partials.basic-info')
        </div>

        <!-- Tab 2: Quản Lý Ảnh -->
        <div class="tab-pane fade" id="images" role="tabpanel">
            @include('admin.products.partials.image-management')
        </div>

        <!-- Tab 3: Variants -->
        <div class="tab-pane fade" id="variants" role="tabpanel">
            @include('admin.products.partials.variant-management')
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 600;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 12px 24px;
}

.nav-tabs .nav-link:hover {
    border-color: #dee2e6;
    color: #495057;
}

.nav-tabs .nav-link.active {
    color: #667eea;
    border-bottom-color: #667eea;
    background: transparent;
}
</style>
@endsection
