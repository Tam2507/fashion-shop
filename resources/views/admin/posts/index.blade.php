@extends('layouts.admin')

@section('title', 'Quản Lý Bài Viết')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản Lý Bài Viết</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Bài Viết Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($posts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Ảnh</th>
                                <th>Tiêu Đề</th>
                                <th>Tác Giả</th>
                                <th style="width: 120px;">Thứ Tự</th>
                                <th style="width: 100px;">Trạng Thái</th>
                                <th style="width: 150px;">Ngày Tạo</th>
                                <th style="width: 150px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>
                                    @if($post->featured_image)
                                        <img src="{{ \App\Services\ImageUploadService::url($post->featured_image) }}" 
                                             alt="{{ $post->title }}" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $post->title }}</strong>
                                    @if($post->excerpt)
                                        <br><small class="text-muted">{{ Str::limit($post->excerpt, 60) }}</small>
                                    @endif
                                </td>
                                <td>{{ $post->author->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $post->display_order }}</span>
                                </td>
                                <td>
                                    @if($post->is_published)
                                        <span class="badge bg-success">Đã xuất bản</span>
                                    @else
                                        <span class="badge bg-warning">Nháp</span>
                                    @endif
                                </td>
                                <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.posts.edit', $post) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
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
            @else
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có bài viết nào. Hãy tạo bài viết đầu tiên!</p>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Bài Viết Mới
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
