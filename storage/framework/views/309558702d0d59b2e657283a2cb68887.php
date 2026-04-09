

<?php $__env->startSection('title', $post->title . ' - Blog'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Rating Input Styles */
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }
    .rating-input input[type="radio"] {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        font-size: 2rem;
        color: #ddd;
        transition: color 0.2s;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input[type="radio"]:checked ~ label {
        color: #ffc107;
    }
    
    /* Rating Display Styles */
    .rating-display .star {
        color: #ddd;
        font-size: 1rem;
    }
    .rating-display .star.filled {
        color: #ffc107;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="<?php echo e(route('home')); ?>#blog" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay Lại Trang Chủ
                </a>
            </div>

            <!-- Post Header -->
            <article class="blog-post">
                <h1 class="display-5 mb-3"><?php echo e($post->title); ?></h1>
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <span class="text-muted">
                            <i class="fas fa-user"></i> <?php echo e($post->author->name); ?>

                        </span>
                        <span class="text-muted ms-3">
                            <i class="fas fa-calendar"></i> <?php echo e($post->created_at->format('d/m/Y')); ?>

                        </span>
                    </div>
                </div>

                <!-- Featured Image -->
                <?php if($post->featured_image): ?>
                    <div class="mb-4">
                        <img src="<?php echo e(asset('storage/' . $post->featured_image)); ?>" 
                             alt="<?php echo e($post->title); ?>" 
                             class="img-fluid rounded shadow">
                    </div>
                <?php endif; ?>

                <!-- Excerpt -->
                <?php if($post->excerpt): ?>
                    <div class="lead mb-4 text-muted">
                        <?php echo e($post->excerpt); ?>

                    </div>
                <?php endif; ?>

                <!-- Content -->
                <div class="post-content">
                    <?php echo nl2br(e($post->content)); ?>

                </div>

                <!-- Post Images Gallery -->
                <?php if($post->images->count() > 0): ?>
                    <div class="post-images-gallery mt-5">
                        <h5 class="mb-3">Hình Ảnh</h5>
                        <div class="row g-3">
                            <?php $__currentLoopData = $post->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="image-item">
                                        <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                             alt="<?php echo e($image->caption ?? $post->title); ?>" 
                                             class="img-fluid rounded shadow-sm"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageModal<?php echo e($image->id); ?>"
                                             style="cursor: pointer; width: 100%; height: 300px; object-fit: cover;">
                                        <?php if($image->caption): ?>
                                            <p class="text-muted small mt-2 mb-0"><?php echo e($image->caption); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Image Modal -->
                                    <div class="modal fade" id="imageModal<?php echo e($image->id); ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?php echo e($image->caption ?? $post->title); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                                         alt="<?php echo e($image->caption ?? $post->title); ?>" 
                                                         class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </article>

            <!-- Comments Section -->
            <div class="mt-5 pt-4 border-top">
                <h5 class="mb-4">
                    <i class="fas fa-comments"></i> Bình Luận (<?php echo e($post->comments->count()); ?>)
                </h5>

                <!-- Comments List -->
                <?php if($post->comments->count() > 0): ?>
                    <div class="comments-list mb-4">
                        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="comment-item mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="text-primary">
                                            <i class="fas fa-user-circle"></i> <?php echo e($comment->author_name); ?>

                                        </strong>
                                        <small class="text-muted ms-2">
                                            <i class="fas fa-clock"></i> <?php echo e($comment->created_at->diffForHumans()); ?>

                                        </small>
                                        <?php if($comment->rating): ?>
                                            <div class="rating-display d-inline-block ms-2">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <span class="star <?php echo e($i <= $comment->rating ? 'filled' : ''); ?>">★</span>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="mb-0"><?php echo e($comment->content); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                <?php endif; ?>

                <!-- Comment Form -->
                <div class="comment-form">
                    <h6 class="mb-3">Để Lại Bình Luận</h6>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('posts.comments.store', $post)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <?php if(auth()->guard()->guest()): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="guest_name" class="form-label">Họ Tên <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['guest_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="guest_name" 
                                           name="guest_name" 
                                           value="<?php echo e(old('guest_name')); ?>" 
                                           required>
                                    <?php $__errorArgs = ['guest_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="guest_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control <?php $__errorArgs = ['guest_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="guest_email" 
                                           name="guest_email" 
                                           value="<?php echo e(old('guest_email')); ?>" 
                                           required>
                                    <?php $__errorArgs = ['guest_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Đánh Giá Bài Viết</label>
                            <div class="rating-input">
                                <input type="radio" name="rating" value="5" id="star5">
                                <label for="star5" title="5 sao">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" title="4 sao">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" title="3 sao">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" title="2 sao">★</label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" title="1 sao">★</label>
                            </div>
                            <small class="text-muted">Tùy chọn - Đánh giá từ 1 đến 5 sao</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội Dung Bình Luận <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="content" 
                                      name="content" 
                                      rows="4" 
                                      placeholder="Nhập bình luận của bạn..." 
                                      required><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(route('posts.show', $post->slug))); ?>" 
                       target="_blank" 
                       class="btn btn-primary">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(route('posts.show', $post->slug))); ?>&text=<?php echo e(urlencode($post->title)); ?>" 
                       target="_blank" 
                       class="btn btn-info text-white">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                </div>
            </div>

            <!-- Related Posts -->
            <?php
                $relatedPosts = \App\Models\Post::published()
                    ->where('id', '!=', $post->id)
                    ->ordered()
                    ->limit(3)
                    ->get();
            ?>

            <?php if($relatedPosts->count() > 0): ?>
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-4">Bài Viết Liên Quan</h5>
                    <div class="row g-3">
                        <?php $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <?php if($relatedPost->featured_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $relatedPost->featured_image)); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo e($relatedPost->title); ?>"
                                         style="height: 150px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo e(Str::limit($relatedPost->title, 50)); ?></h6>
                                    <a href="<?php echo e(route('posts.show', $relatedPost->slug)); ?>" class="btn btn-sm btn-primary">
                                        Đọc Thêm
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/posts/show.blade.php ENDPATH**/ ?>