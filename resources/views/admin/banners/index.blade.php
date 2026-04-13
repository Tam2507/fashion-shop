@extends('layouts.admin')

@section('title', 'Quản Lý Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-images"></i> Quản Lý Banner</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Banner Mới
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Trang</th>
                        <th>Loại</th>
                        <th>Vị trí</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                    <tr>
                        <td>
                            @if($banner->image)
                                <img src="{{ \App\Services\ImageUploadService::url($banner->image) }}"  alt="{{ $banner->title }}" 
                                     class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 50px; border-radius: 4px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $banner->title }}</strong>
                            @if($banner->subtitle)
                                <br><small class="text-muted">{{ $banner->subtitle }}</small>
                            @endif
                        </td>
                        <td>
                            @switch($banner->page ?? 'all')
                                @case('home')
                                    <span class="badge bg-success">Trang chủ</span>
                                    @break
                                @case('products')
                                    <span class="badge bg-info">Sản phẩm</span>
                                    @break
                                @case('all')
                                @default
                                    <span class="badge bg-secondary">Tất cả</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
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
                        </td>
                        <td>{{ $banner->position }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" 
                                       data-id="{{ $banner->id }}" {{ $banner->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    <span class="status-text">{{ $banner->is_active ? 'Hoạt động' : 'Tạm dừng' }}</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.banners.show', $banner) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" 
                                      class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-images text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Chưa có banner nào</p>
                            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo Banner Đầu Tiên
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const bannerId = this.dataset.id;
            const statusText = this.closest('td').querySelector('.status-text');
            
            fetch(`/admin/banners/${bannerId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusText.textContent = data.is_active ? 'Hoạt động' : 'Tạm dừng';
                    
                    // Show toast notification
                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">${data.message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    
                    toast.addEventListener('hidden.bs.toast', function() {
                        document.body.removeChild(toast);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; // Revert toggle
            });
        });
    });
});
</script>
@endsection