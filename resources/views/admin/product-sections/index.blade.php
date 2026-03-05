@extends('layouts.admin')

@section('title', 'Quản Lý Section Sản Phẩm')
@section('page_title', 'Quản Lý Section Sản Phẩm')
@section('header_icon', 'fas fa-layer-group')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-layer-group"></i> Danh Sách Section</h5>
            <a href="{{ route('admin.product-sections.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Section Mới
            </a>
        </div>
        <div class="card-body">
            @if($sections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">STT</th>
                                <th>Tên Section</th>
                                <th>Slug</th>
                                <th>Số Sản Phẩm</th>
                                <th>Tối Đa</th>
                                <th>Thứ Tự</th>
                                <th>Trạng Thái</th>
                                <th width="200">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sections as $section)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $section->name }}</strong>
                                        @if($section->description)
                                            <br><small class="text-muted">{{ Str::limit($section->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $section->slug }}</code></td>
                                    <td>
                                        <span class="badge bg-info">{{ $section->products_count }} sản phẩm</span>
                                    </td>
                                    <td>{{ $section->max_products }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $section->display_order }}</span>
                                    </td>
                                    <td>
                                        @if($section->is_active)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-danger">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.product-sections.edit', $section) }}" 
                                               class="btn btn-sm btn-primary" title="Quản lý sản phẩm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.product-sections.destroy', $section) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa section này?')"
                                                  class="d-inline">
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
                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có section nào. Hãy tạo section đầu tiên!</p>
                    <a href="{{ route('admin.product-sections.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Section Mới
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
