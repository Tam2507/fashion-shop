@extends('layouts.messenger')

@section('title', 'Tin Nhắn')

@section('styles')
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
@endsection

@section('content')
<!-- Chat Header -->
<div class="chat-header">
    @if($firstMessage->user && $firstMessage->user->avatar)
        <img src="/storage/{{ $firstMessage->user->avatar }}" 
             alt="{{ $firstMessage->user->name }}" 
             class="chat-header-avatar">
    @else
        <div class="chat-header-avatar-placeholder">
            <i class="fas fa-user"></i>
        </div>
    @endif

    <div class="chat-header-info">
        <h6>
            @if($firstMessage->user)
                {{ $firstMessage->user->name }}
            @else
                {{ $firstMessage->guest_name }}
            @endif
        </h6>
        <small>
            @if($firstMessage->user)
                {{ $firstMessage->user->email }}
            @else
                {{ $firstMessage->guest_email }}
            @endif
        </small>
    </div>
</div>

<!-- Chat Messages -->
<div class="chat-messages" id="chatMessages">
    @foreach($messages as $message)
        @php
            // Tin nhắn của admin (đang đăng nhập) ở bên phải
            $isMyMessage = $message->is_admin_reply;
        @endphp
        
        <div class="message-group {{ $isMyMessage ? 'my-message' : 'other-message' }}">
            @if(!$isMyMessage)
                @if($message->user && $message->user->avatar)
                    <img src="/storage/{{ $message->user->avatar }}" 
                         alt="{{ $message->user->name }}" 
                         class="message-avatar">
                @else
                    <div class="message-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            @endif

            <div class="message-content">
                <div class="message-bubble">
                    {{ $message->message }}
                </div>
                <div class="message-time">
                    {{ $message->created_at->format('H:i') }}
                </div>
            </div>

            @if($isMyMessage)
                @if(auth()->user()->avatar)
                    <img src="/storage/{{ auth()->user()->avatar }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="message-avatar">
                @else
                    <div class="message-avatar-placeholder">
                        <i class="fas fa-user-shield"></i>
                    </div>
                @endif
            @endif
        </div>
    @endforeach
</div>

<!-- Chat Input -->
<div class="chat-input">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.messages.reply', $firstMessage->id) }}" method="POST" class="chat-input-form">
        @csrf
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
@endsection

@section('scripts')
@endsection
