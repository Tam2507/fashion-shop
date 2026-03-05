@extends('layouts.app')

@section('title', 'Tin Nhắn')

@section('content')
<style>
.messenger-container {
    height: calc(100vh - 200px);
    max-width: 900px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chat-header {
    padding: 20px;
    border-bottom: 1px solid #e4e6eb;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.chat-header h4 {
    margin: 0;
    font-weight: 600;
}

.chat-header p {
    margin: 4px 0 0 0;
    font-size: 14px;
    opacity: 0.9;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f0f2f5;
}

.message-group {
    margin-bottom: 16px;
    display: flex;
    gap: 8px;
}

.message-group.my-message {
    justify-content: flex-end;
}

.message-group.other-message {
    justify-content: flex-start;
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    align-self: flex-end;
}

.message-avatar-placeholder {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    align-self: flex-end;
}

.message-content {
    max-width: 60%;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 4px;
    word-wrap: break-word;
}

.my-message .message-bubble {
    background: #0084ff;
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
    color: #65676b;
    padding: 0 12px;
}

.chat-input {
    padding: 16px 20px;
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
    border: 1px solid #e4e6eb;
    border-radius: 20px;
    padding: 10px 16px;
    resize: none;
    font-size: 15px;
    max-height: 100px;
}

.chat-input-form textarea:focus {
    outline: none;
    border-color: #0084ff;
}

.chat-input-form button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #0084ff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.chat-input-form button:hover {
    background: #0073e6;
}

.empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #65676b;
    padding: 40px;
    text-align: center;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    color: #e4e6eb;
}

.login-prompt {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    margin: 20px;
}

.login-prompt h5 {
    color: #856404;
    margin-bottom: 10px;
}

.login-prompt p {
    color: #856404;
    margin-bottom: 15px;
}
</style>

<div class="container py-4">
    <div class="messenger-container">
        <div class="chat-header">
            <h4><i class="fas fa-comments"></i> Tin Nhắn với Shop</h4>
            <p>Gửi tin nhắn cho chúng tôi, chúng tôi sẽ phản hồi sớm nhất!</p>
        </div>

        @auth
            <div class="chat-messages" id="chatMessages">
                <div class="empty-state" id="emptyState">
                    <i class="fas fa-comments"></i>
                    <h5>Chưa có tin nhắn</h5>
                    <p>Gửi tin nhắn đầu tiên của bạn cho chúng tôi!</p>
                </div>
                <div id="messagesList" style="display: none;"></div>
            </div>

            <div class="chat-input">
                <form id="messageForm" class="chat-input-form">
                    @csrf
                    <textarea name="message" 
                              id="messageInput"
                              placeholder="Nhập tin nhắn..." 
                              rows="1" 
                              required></textarea>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        @else
            <div class="login-prompt">
                <h5><i class="fas fa-lock"></i> Vui lòng đăng nhập</h5>
                <p>Bạn cần đăng nhập để có thể gửi tin nhắn cho chúng tôi</p>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </a>
            </div>
        @endauth
    </div>
</div>

@auth
<script>
let isLoading = false;

// Load messages
async function loadMessages() {
    try {
        const response = await fetch('{{ route('messages.index') }}');
        const data = await response.json();
        
        const messagesList = document.getElementById('messagesList');
        const emptyState = document.getElementById('emptyState');
        
        if (data.messages && data.messages.length > 0) {
            emptyState.style.display = 'none';
            messagesList.style.display = 'block';
            messagesList.innerHTML = '';
            
            data.messages.forEach(msg => {
                const isMyMessage = !msg.is_admin_reply;
                const messageHTML = `
                    <div class="message-group ${isMyMessage ? 'my-message' : 'other-message'}">
                        ${!isMyMessage ? `
                            <div class="message-avatar-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        ` : ''}
                        
                        <div class="message-content">
                            <div class="message-bubble">
                                ${msg.message}
                            </div>
                            <div class="message-time ${isMyMessage ? 'text-end' : 'text-start'}">
                                ${formatTime(msg.created_at)}
                            </div>
                        </div>
                        
                        ${isMyMessage ? `
                            @if(auth()->user()->avatar)
                                <img src="/storage/{{ auth()->user()->avatar }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="message-avatar">
                            @else
                                <div class="message-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        ` : ''}
                    </div>
                `;
                messagesList.insertAdjacentHTML('beforeend', messageHTML);
            });
            
            scrollToBottom();
        } else {
            emptyState.style.display = 'flex';
            messagesList.style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

// Send message
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (isLoading) return;
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    isLoading = true;
    
    try {
        const response = await fetch('{{ route('messages.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            await loadMessages();
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại!');
    } finally {
        isLoading = false;
    }
});

// Auto resize textarea
document.getElementById('messageInput').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});

// Enter to send
document.getElementById('messageInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('messageForm').dispatchEvent(new Event('submit'));
    }
});

function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function formatTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    
    if (diff < 86400000) { // Less than 24 hours
        return `${hours}:${minutes}`;
    } else {
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        return `${day}/${month} ${hours}:${minutes}`;
    }
}

// Load messages on page load
loadMessages();

// Auto refresh every 5 seconds
setInterval(loadMessages, 5000);
</script>
@endauth
@endsection
