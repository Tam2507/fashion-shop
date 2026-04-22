@extends('layouts.app')

@section('title', 'Blog - Fashion Shop')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fas fa-newspaper me-2"></i>Blog</h2>
    </div>

    @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    @if($post->featured_image)
                        <a href="{{ route('posts.show', $post->slug) }}">
                            <img src="{{ \App\Services\ImageUploadService::url($post->featured_image) }}"
                                 class="card-img-top" style="height:200px;object-fit:cover;"
                                 alt="{{ $post->title }}">
                        </a>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-dark text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h5>
                        @if($post->excerpt)
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($post->excerpt, 120) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-outline-dark rounded-pill">
                                Đọc thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-newspaper fa-3x mb-3 opacity-25"></i>
            <p>Chưa có bài viết nào.</p>
        </div>
    @endif
</div>
@endsection
