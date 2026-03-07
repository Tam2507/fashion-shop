@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Chỉnh Sửa Banner #{{ $banner->id }}</h1>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <input type="hidden" name="title" value="{{ $banner->title }}">

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh Banner *</label>
                        @if($banner->image)
                            <div class="mb-3">
                                <label class="form-label">Ảnh hiện tại:</label>
                                <div>
                                    <img src="/storage/{{ $banner->image }}" alt="Banner" 
                                         class="img-thumbnail mb-2" style="max-width: 100%; max-height: 200px;">
                                </div>
                                <small class="text-muted d-block mb-2">Upload ảnh mới để thay thế</small>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chấp nhận: JPG, PNG, GIF, WEBP. Tối đa 2MB. Kích thước đề xuất: 1920x400px</div>
                    </div>

                    <div class="mb-3">
                        <label for="link_url" class="form-label">Link URL (khi click vào banner)</label>
                        <input type="text" class="form-control @error('link_url') is-invalid @enderror" 
                               id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}" 
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
                                <option value="home" {{ old('page', $banner->page) == 'home' ? 'selected' : '' }}>Trang chủ</option>
                                <option value="products" {{ old('page', $banner->page) == 'products' ? 'selected' : '' }}>Trang sản phẩm</option>
                                <option value="all" {{ old('page', $banner->page) == 'all' ? 'selected' : '' }}>Tất cả trang</option>
                            </select>
                            @error('page')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Thứ tự hiển thị *</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', $banner->position) }}" min="0" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước</div>
                        </div>
                    </div>

                    <input type="hidden" name="banner_type" value="{{ $banner->banner_type }}">
                    <input type="hidden" name="is_active" value="on">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập Nhật Banner
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
                <h5><i class="fas fa-info-circle"></i> Thông tin</h5>
            </div>
            <div class="card-body">
                <p><strong>Vị trí:</strong> {{ $banner->position }}</p>
                <p><strong>Trang:</strong> {{ $banner->page }}</p>
                <p><strong>Loại:</strong> {{ $banner->banner_type }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">
                        {{ $banner->is_active ? 'Đang hoạt động' : 'Tạm dừng' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Simple file input - no complex JavaScript needed
</script>
@endsection
