@extends('layouts.admin')

@section('title', 'Tạo Banner Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-plus"></i> Tạo Banner Mới</h1>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input type="hidden" name="title" value="Banner {{ date('Y-m-d H:i:s') }}">

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh Banner *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chấp nhận: JPG, PNG, WEBP. Tối đa 2MB. Kích thước đề xuất: 1920x400px</div>
                    </div>

                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL (khi click vào banner)</label>
                        <input type="text" class="form-control @error('link_url') is-invalid @enderror" 
                               id="link_url" name="link_url" value="{{ old('link_url') }}" 
                               placeholder="/products hoặc https://example.com">
                        @error('link_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Để trống sẽ chuyển đến trang sản phẩm mặc định</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="page" class="form-label">Hiển thị tại trang *</label>
                            <select class="form-select @error('page') is-invalid @enderror" id="page" name="page" required>
                                <option value="home" {{ old('page') == 'home' ? 'selected' : '' }}>Trang chủ</option>
                                <option value="products" {{ old('page') == 'products' ? 'selected' : '' }}>Trang sản phẩm</option>
                                <option value="all" {{ old('page', 'home') == 'all' ? 'selected' : '' }}>Tất cả trang</option>
                            </select>
                            @error('page')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Thứ tự hiển thị *</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', 0) }}" min="0" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước</div>
                        </div>
                    </div>

                    <input type="hidden" name="banner_type" value="hero">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Tạo Banner
                        </button>
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
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
                <h5><i class="fas fa-info-circle"></i> Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <p><strong>Trang hiển thị:</strong></p>
                <ul>
                    <li><strong>Trang chủ:</strong> Banner chỉ hiển thị ở trang chủ</li>
                    <li><strong>Trang sản phẩm:</strong> Banner chỉ hiển thị ở trang sản phẩm</li>
                    <li><strong>Tất cả trang:</strong> Banner hiển thị ở mọi trang</li>
                </ul>
                <p class="mt-3"><strong>Thứ tự hiển thị:</strong></p>
                <p>Banner có số thứ tự nhỏ hơn sẽ hiển thị trước. Ví dụ: Banner có position = 1 sẽ hiển thị trước banner có position = 2.</p>
            </div>
        </div>
    </div>
</div>
@endsection
