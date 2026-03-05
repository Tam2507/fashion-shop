@extends('layouts.app')

@section('content')
<h1>Thêm danh mục</h1>
<form method="POST" action="{{ route('admin.categories.store') }}">@csrf
    <div class="mb-3"><label class="form-label">Tên</label><input name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Mô tả</label><textarea name="description" class="form-control"></textarea></div>
    <button class="btn btn-primary">Lưu</button>
</form>
@endsection