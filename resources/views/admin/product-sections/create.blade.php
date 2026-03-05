@extends('layouts.admin')

@section('title', 'Tạo Section Mới')
@section('page_title', 'Tạo Section Sản Phẩm Mới')
@section('header_icon', 'fas fa-plus-circle')

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Thông Tin Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product-sections.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Section *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required placeholder="VD: Sản Phẩm Nổi Bật">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Tên hiển thị trên trang chủ</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug') }}" placeholder="VD: featured-products">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Để trống để tự động tạo từ tên. Chỉ dùng chữ thường, số và dấu gạch ngang</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô Tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Mô tả ngắn về section này">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số Sản Phẩm Tối Đa *</label>
                                <input type="number" name="max_products" class="form-control @error('max_products') is-invalid @enderror" 
                                       value="{{ old('max_products', 8) }}" min="1" max="50" required>
                                @error('max_products')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Số sản phẩm hiển thị trên trang chủ (1-50)</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Thứ Tự Hiển Thị *</label>
                                <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" 
                                       value="{{ old('display_order', 0) }}" min="0" required>
                                @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Số nhỏ hơn hiển thị trước</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Hiển thị trên trang chủ</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo Section
                            </button>
                            <a href="{{ route('admin.product-sections.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
