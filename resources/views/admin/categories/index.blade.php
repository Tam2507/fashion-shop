@extends('layouts.app')

@section('content')
<h1>Quản lý Danh mục</h1>
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm danh mục</a>
<table class="table">
    <thead><tr><th>ID</th><th>Tên</th><th>Mô tả</th><th>Hành động</th></tr></thead>
    <tbody>
        @foreach($categories as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ Str::limit($c->description, 60) }}</td>
            <td>
                <a class="btn btn-sm btn-secondary" href="{{ route('admin.categories.edit', $c->id) }}">Sửa</a>
                <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" style="display:inline">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">{{ $categories->links() }}</div>
@endsection