@extends('layouts.app')

@section('content')
<h1>Sửa danh mục</h1>
<form method="POST" action="{{ route('admin.categories.update', $category->id) }}">@csrf @method('PUT')
    <div class="mb-3"><label class="form-label">Tên</label><input name="name" class="form-control" value="{{ $category->name }}" required></div>
    <div class="mb-3"><label class="form-label">Mô tả</label><textarea name="description" class="form-control">{{ $category->description }}</textarea></div>
    <button class="btn btn-primary">Lưu</button>
</form>
@endsection