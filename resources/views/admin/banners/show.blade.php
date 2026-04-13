@extends('layouts.admin')

@section('title', 'Chi Tiết Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-eye"></i> Chi Tiết Banner</h1>
    <div class="btn-group">
        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Tiêu đề:</strong></div>
                    <div class="col-sm-9">{{ $banner->title }}</div>
                </div>
                
                @if($banner->subtitle)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Phụ đề:</strong></div>
                    <div class="col-sm-9">{{ $banner->subtitle }}</div>
                </div>
                @endif
                
                @if($banner->description)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Mô tả:</strong></div>
                    <div class="col-sm-9">{{ $banner->description }}</div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Loại banner:</strong></div>
                    <div class="col-sm-9">
                        @switch($banner->banner_type)
                            @case('hero')
                                <span class="badge bg-primary">Hero</span>
                                @break
                            @case('promotion')
                                <span class="badge bg-warning">Khuyến mãi</span>
                                @break
                            @case('announcement')
                                <span class="badge bg-info">Thông báo</span>
                                @break
                        @endswitch
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Vị trí:</strong></div>
                    <div class="col-sm-9">{{ $banner->position }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Trạng thái:</strong></div>
                    <div class="col-sm-9">
                        @if($banner->is_active)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Tạm dừng</span>
                        @endif
                    </div>
                </div>
                
                @if($banner->link_url)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Liên kết:</strong></div>
                    <div class="col-sm-9">
                        <a href="{{ $banner->link_url }}" target="_blank">{{ $banner->link_url }}</a>
                        @if($banner->link_text)
                            <br><small class="text-muted">Text nút: {{ $banner->link_text }}</small>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Màu sắc:</strong></div>
                    <div class="col-sm-9">
                        <span class="d-inline-block me-2" style="width: 20px; height: 20px; background-color: {{ $banner->background_color }}; border: 1px solid #ddd;"></span>
                        Nền: {{ $banner->background_color }}
                        <span class="d-inline-block ms-3 me-2" style="width: 20px; height: 20px; background-color: {{ $banner->text_color }}; border: 1px solid #ddd;"></span>
                        Chữ: {{ $banner->text_color }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Ngày tạo:</strong></div>
                    <div class="col-sm-9">{{ $banner->created_at->format('d/m/Y H:i') }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Cập nhật:</strong></div>
                    <div class="col-sm-9">{{ $banner->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Image -->
        @if($banner->image)
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-image"></i> Hình ảnh</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ \App\Services\ImageUploadService::url($banner->image) }}"  alt="{{ $banner->title }}" 
                     class="img-fluid rounded">
            </div>
        </div>
        @endif
        
        <!-- Preview -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-eye"></i> Xem trước</h5>
            </div>
            <div class="card-body">
                <div class="banner-preview" style="background-color: {{ $banner->background_color }}; color: {{ $banner->text_color }}; padding: 20px; border-radius: 8px; text-align: center; min-height: 150px; display: flex; flex-direction: column; justify-content: center;">
                    @if($banner->image)
                        <img src="{{ \App\Services\ImageUploadService::url($banner->image) }}"  alt="{{ $banner->title }}" 
                             style="max-width: 100%; height: auto; margin-bottom: 10px;">
                    @endif
                    <h5 style="color: {{ $banner->text_color }}; margin-bottom: 8px;">{{ $banner->title }}</h5>
                    @if($banner->subtitle)
                        <p style="color: {{ $banner->text_color }}; opacity: 0.8; margin-bottom: 8px; font-size: 0.9rem;">{{ $banner->subtitle }}</p>
                    @endif
                    @if($banner->description)
                        <p style="color: {{ $banner->text_color }}; font-size: 0.8rem; margin-bottom: 10px;">{{ Str::limit($banner->description, 80) }}</p>
                    @endif
                    @if($banner->link_url && $banner->link_text)
                        <a href="{{ $banner->link_url }}" class="btn btn-light btn-sm">{{ $banner->link_text }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection