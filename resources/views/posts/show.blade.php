@extends('layouts.app')

@section('title', $post->title . ' - Blog')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('home') }}#blog" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay Lại Trang Chủ
                </a>
            </div>

            <!-- Post Header -->
            <article class="blog-post">
                <h1 class="display-5 mb-3">{{ $post->title }}</h1>
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <span class="text-muted">
                            <i class="fas fa-user"></i> {{ $post->author->name }}
                        </span>
                        <span class="text-muted ms-3">
                            <i class="fas fa-calendar"></i> {{ $post->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->featured_image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             alt="{{ $post->title }}" 
                             class="img-fluid rounded shadow">
                    </div>
                @endif

                <!-- Excerpt -->
                @if($post->excerpt)
                    <div class="lead mb-4 text-muted">
                        {{ $post->excerpt }}
                    </div>
                @endif

                <!-- Content -->
                <div class="post-content">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Post Images Gallery -->
                @if($post->images->count() > 0)
                    <div class="post-images-gallery mt-5">
                        <h5 class="mb-3">Hình Ảnh</h5>
                        <div class="row g-3">
                            @foreach($post->images as $image)
                                <div class="col-md-6">
                                    <div class="image-item">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="{{ $image->caption ?? $post->title }}" 
                                             class="img-fluid rounded shadow-sm"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageModal{{ $image->id }}"
                                             style="cursor: pointer; width: 100%; height: 300px; object-fit: cover;">
                                        @if($image->caption)
                                            <p class="text-muted small mt-2 mb-0">{{ $image->caption }}</p>
                                        @endif
                                    </div>

                                    <!-- Image Modal -->
                                    <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $image->caption ?? $post->title }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                         alt="{{ $image->caption ?? $post->title }}" 
                                                         class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <!-- Comments Section -->
            <div class="mt-5 pt-4 border-top">
                <h5 class="mb-4">
                    <i class="fas fa-comments"></i> Bình Luận ({{ $post->comments->count() }})
                </h5>

                <!-- Comments List -->
                @if($post->comments->count() > 0)
                    <div class="comments-list mb-4">
                        @foreach($post->comments as $comment)
                            <div class="comment-item mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="text-primary">
                                            <i class="fas fa-user-circle"></i> {{ $comment->author_name }}
                                        </strong>
                                        <small class="text-muted ms-2">
                                            <i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-4">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                @endif

                <!-- Comment Form -->
                <div class="comment-form">
                    <h6 class="mb-3">Để Lại Bình Luận</h6>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                        @csrf
                        
                        @guest
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="guest_name" class="form-label">Họ Tên <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('guest_name') is-invalid @enderror" 
                                           id="guest_name" 
                                           name="guest_name" 
                                           value="{{ old('guest_name') }}" 
                                           required>
                                    @error('guest_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('guest_email') is-invalid @enderror" 
                                           id="guest_email" 
                                           name="guest_email" 
                                           value="{{ old('guest_email') }}" 
                                           required>
                                    @error('guest_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endguest

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội Dung Bình Luận <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="4" 
                                      placeholder="Nhập bình luận của bạn..." 
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi Bình Luận
                        </button>
                    </form>
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="mt-5 pt-4 border-top">
                <h5 class="mb-3">Chia Sẻ Bài Viết</h5>
                <div class="d-flex gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('posts.show', $post->slug)) }}" 
                       target="_blank" 
                       class="btn btn-primary">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('posts.show', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                       target="_blank" 
                       class="btn btn-info text-white">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                </div>
            </div>

            <!-- Related Posts -->
            @php
                $relatedPosts = \App\Models\Post::published()
                    ->where('id', '!=', $post->id)
                    ->ordered()
                    ->limit(3)
                    ->get();
            @endphp

            @if($relatedPosts->count() > 0)
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-4">Bài Viết Liên Quan</h5>
                    <div class="row g-3">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-4">
                            <div class="card h-100">
                                @if($relatedPost->featured_image)
                                    <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $relatedPost->title }}"
                                         style="height: 150px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($relatedPost->title, 50) }}</h6>
                                    <a href="{{ route('posts.show', $relatedPost->slug) }}" class="btn btn-sm btn-primary">
                                        Đọc Thêm
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .blog-post h1 {
        font-family: 'Playfair Display', serif;
        color: var(--primary);
        font-weight: 700;
    }
    
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }
    
    .post-content p {
        margin-bottom: 1.5rem;
    }

    .post-images-gallery .image-item {
        transition: transform 0.3s ease;
    }

    .post-images-gallery .image-item:hover {
        transform: translateY(-5px);
    }

    .post-images-gallery img {
        transition: all 0.3s ease;
    }

    .post-images-gallery img:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important;
    }

    .comment-item {
        border-left: 3px solid var(--primary);
        transition: all 0.3s ease;
    }

    .comment-item:hover {
        background-color: #e9ecef !important;
        transform: translateX(5px);
    }

    .comment-form textarea {
        resize: vertical;
    }

    .comments-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .comments-list::-webkit-scrollbar {
        width: 8px;
    }

    .comments-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .comments-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .comments-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection
