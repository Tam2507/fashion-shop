@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tags"></i> Quản Lý Danh Mục</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Danh Mục Mới
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td><span class="text-muted">{{ Str::limit($c->description, 80) ?: '—' }}</span></td>
                        <td>
                            <span class="badge bg-info">{{ $c->products_count ?? $c->products()->count() }} sản phẩm</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.categories.edit', $c->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-tags text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Chưa có danh mục nào</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo Danh Mục Đầu Tiên
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
