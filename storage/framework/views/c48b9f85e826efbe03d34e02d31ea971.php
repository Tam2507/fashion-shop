

<?php $__env->startSection('title', 'Tin Nhắn'); ?>

<?php $__env->startSection('styles'); ?>
<style>
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #fff;
}

.message-group {
    margin-bottom: 8px;
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.message-group.my-message {
    justify-content: flex-end;
}

.message-group.other-message {
    justify-content: flex-start;
}

.message-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.message-avatar-placeholder {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #bcc0c4;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.message-content {
    max-width: 55%;
    display: flex;
    flex-direction: column;
}

.my-message .message-content {
    align-items: flex-end;
}

.other-message .message-content {
    align-items: flex-start;
}

.message-bubble {
    padding: 8px 12px;
    border-radius: 18px;
    word-wrap: break-word;
    font-size: 15px;
    line-height: 1.4;
    display: inline-block;
}

.my-message .message-bubble {
    background: linear-gradient(135deg, #0084ff 0%, #0063d1 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.other-message .message-bubble {
    background: #e4e6eb;
    color: #050505;
    border-bottom-left-radius: 4px;
}

.message-time {
    font-size: 11px;
    color: #8a8d91;
    margin-top: 2px;
    padding: 0 8px;
}

.chat-header {
    padding: 12px 20px;
    border-bottom: 1px solid #e4e6eb;
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fff;
}

.chat-header-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-header-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #bcc0c4;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.chat-header-info h6 {
    margin: 0;
    font-weight: 600;
    font-size: 15px;
    color: #050505;
}

.chat-header-info small {
    color: #65676b;
    font-size: 13px;
}

.chat-input {
    padding: 12px 20px;
    border-top: 1px solid #e4e6eb;
    background: #fff;
}

.chat-input-form {
    display: flex;
    gap: 8px;
    align-items: flex-end;
}

.chat-input-form textarea {
    flex: 1;
    border: 1px solid #ccd0d5;
    border-radius: 20px;
    padding: 10px 16px;
    resize: none;
    font-size: 15px;
    max-height: 100px;
    font-family: inherit;
    background: #f0f2f5;
}

.chat-input-form textarea:focus {
    outline: none;
    background: #fff;
    border-color: #0084ff;
}

.chat-input-form button {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #0084ff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
    flex-shrink: 0;
}

.chat-input-form button:hover {
    background: #0073e6;
}

.chat-input-form button:disabled {
    background: #bcc0c4;
    cursor: not-allowed;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Chat Header -->
<div class="chat-header">
    <?php if($firstMessage->user && $firstMessage->user->avatar): ?>
        <img src="/storage/<?php echo e($firstMessage->user->avatar); ?>" 
             alt="<?php echo e($firstMessage->user->name); ?>" 
             class="chat-header-avatar">
    <?php else: ?>
        <div class="chat-header-avatar-placeholder">
            <i class="fas fa-user"></i>
        </div>
    <?php endif; ?>

    <div class="chat-header-info">
        <h6>
            <?php if($firstMessage->user): ?>
                <?php echo e($firstMessage->user->name); ?>

            <?php else: ?>
                <?php echo e($firstMessage->guest_name); ?>

            <?php endif; ?>
        </h6>
        <small>
            <?php if($firstMessage->user): ?>
                <?php echo e($firstMessage->user->email); ?>

            <?php else: ?>
                <?php echo e($firstMessage->guest_email); ?>

            <?php endif; ?>
        </small>
    </div>
</div>

<!-- Chat Messages -->
<div class="chat-messages" id="chatMessages">
    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            // Tin nhắn của admin (đang đăng nhập) ở bên phải
            $isMyMessage = $message->is_admin_reply;
        ?>
        
        <div class="message-group <?php echo e($isMyMessage ? 'my-message' : 'other-message'); ?>">
            <?php if(!$isMyMessage): ?>
                <?php if($message->user && $message->user->avatar): ?>
                    <img src="/storage/<?php echo e($message->user->avatar); ?>" 
                         alt="<?php echo e($message->user->name); ?>" 
                         class="message-avatar">
                <?php else: ?>
                    <div class="message-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="message-content">
                <div class="message-bubble">
                    <?php echo e($message->message); ?>

                </div>
                <div class="message-time">
                    <?php echo e($message->created_at->format('H:i')); ?>

                </div>
            </div>

            <?php if($isMyMessage): ?>
                <?php if(auth()->user()->avatar): ?>
                    <img src="/storage/<?php echo e(auth()->user()->avatar); ?>" 
                         alt="<?php echo e(auth()->user()->name); ?>" 
                         class="message-avatar">
                <?php else: ?>
                    <div class="message-avatar-placeholder">
                        <i class="fas fa-user-shield"></i>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Chat Input -->
<div class="chat-input">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.messages.reply', $firstMessage->id)); ?>" method="POST" class="chat-input-form">
        <?php echo csrf_field(); ?>
        <textarea name="message" 
                  placeholder="Aa" 
                  rows="1" 
                  required></textarea>
        <button type="submit">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
</div>

<script>
// Auto scroll to bottom
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Auto resize textarea
const textarea = document.querySelector('textarea[name="message"]');
if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    
    // Enter to send, Shift+Enter for new line
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.form.submit();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.messenger', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Boutique\fashion-shop\resources\views/admin/messages/show.blade.php ENDPATH**/ ?>