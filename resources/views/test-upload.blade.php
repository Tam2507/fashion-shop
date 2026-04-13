<!DOCTYPE html>
<html>
<head>
    <title>Test Upload - Banner {{ $banner->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Upload - Banner: {{ $banner->title }}</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="card mb-4">
            <div class="card-body">
                <h5>Ảnh hiện tại:</h5>
                @if($banner->image)
                    <img src="{{ \App\Services\ImageUploadService::url($banner->image) }}"  alt="{{ $banner->title }}" class="img-thumbnail" style="max-height: 200px;">
                    <p class="mt-2">Path: {{ $banner->image }}</p>
                @else
                    <p class="text-muted">Chưa có ảnh</p>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5>Upload ảnh mới:</h5>
                <form method="POST" action="/test-upload/{{ $banner->id }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <a href="/" class="btn btn-secondary">Về trang chủ</a>
                    <a href="/admin/banners" class="btn btn-info">Quản lý Banner</a>
                </form>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="/test-upload/1" class="btn btn-sm btn-outline-primary">Banner 1</a>
            <a href="/test-upload/2" class="btn btn-sm btn-outline-primary">Banner 2</a>
            <a href="/test-upload/3" class="btn btn-sm btn-outline-primary">Banner 3</a>
            <a href="/test-upload/4" class="btn btn-sm btn-outline-primary">Banner 4</a>
            <a href="/test-upload/5" class="btn btn-sm btn-outline-primary">Banner 5</a>
        </div>
    </div>
</body>
</html>
