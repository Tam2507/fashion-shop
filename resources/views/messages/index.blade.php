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
                    <label for="userImageInput" style="cursor:pointer; margin:0; display:flex; align-items:center; color:#0084ff;" title="Gửi ảnh">
                        <i class="fas fa-image" style="font-size:20px;"></i>
                    </label>
                    <input type="file" id="userImageInput" accept="image/*" style="display:none;">
                    <div style="flex:1; display:flex; flex-direction:column; gap:4px;">
                        <div id="userImgPreview" style="display:none; align-items:center; gap:6px;">
                            <img id="userImgThumb" src="" style="max-height:60px; border-radius:8px; border:1px solid #e4e6eb;">
                            <button type="button" onclick="clearUserImage()" style="background:none;border:none;color:#e74a3b;cursor:pointer;font-size:12px;padding:0 4px;">✕ Xóa</button>
                        </div>
                        <textarea name="message" 
                                  id="messageInput"
                                  placeholder="Nhập tin nhắn..." 
                                  rows="1"></textarea>
                    </div>
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
                const imgHtml = msg.image
                    ? `<img src="/storage/${msg.image}" style="max-width:200px;max-height:200px;border-radius:12px;display:block;cursor:pointer;" onclick="window.open(this.src,'_blank')">`
                    : '';
                const textHtml = msg.message ? `<span style="${msg.image ? 'display:block;margin-top:6px;' : ''}">${msg.message}</span>` : '';
                const messageHTML = `
                    <div class="message-group ${isMyMessage ? 'my-message' : 'other-message'}">
                        ${!isMyMessage ? `
                            <div class="message-avatar-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        ` : ''}
                        
                        <div class="message-content">
                            <div class="message-bubble">
                                ${imgHtml}${textHtml}
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
    const imageInput = document.getElementById('userImageInput');
    const message = messageInput.value.trim();
    const hasImage = imageInput.files && imageInput.files[0];
    
    if (!message && !hasImage) return;
    
    isLoading = true;
    
    try {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (message) formData.append('message', message);
        if (hasImage) formData.append('image', imageInput.files[0]);

        const response = await fetch('{{ route('messages.store') }}', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            clearUserImage();
            await loadMessages();
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại!');
    } finally {
        isLoading = false;
    }
});

function previewUserImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            // Hiển thị thumbnail nhỏ dưới textarea
            const thumb = document.getElementById('userImgThumb');
            const preview = document.getElementById('userImgPreview');
            if (thumb && preview) {
                thumb.src = e.target.result;
                preview.style.display = 'flex';
            }

            // Xóa preview cũ trong chat nếu có
            const old = document.getElementById('userChatImgPreview');
            if (old) old.remove();

            const chatMessages = document.getElementById('chatMessages');
            const emptyState = document.getElementById('emptyState');
            if (emptyState) emptyState.style.display = 'none';
            const messagesList = document.getElementById('messagesList');
            if (messagesList) messagesList.style.display = 'block';

            const bubble = document.createElement('div');
            bubble.id = 'userChatImgPreview';
            bubble.className = 'message-group my-message';
            bubble.style.opacity = '0.6';
            bubble.innerHTML = `
                <div class="message-content">
                    <div class="message-bubble" style="padding:6px;">
                        <img src="${e.target.result}" style="max-width:200px;max-height:200px;border-radius:10px;display:block;">
                        <small style="color:#fff;font-size:11px;margin-top:4px;display:block;">Chưa gửi...</small>
                    </div>
                </div>
                @if(auth()->user()->avatar)
                    <img src="/storage/{{ auth()->user()->avatar }}" class="message-avatar">
                @else
                    <div class="message-avatar-placeholder"><i class="fas fa-user"></i></div>
                @endif
            `;
            chatMessages.appendChild(bubble);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearUserImage() {
    document.getElementById('userImageInput').value = '';
    document.getElementById('userImgPreview').style.display = 'none';
    document.getElementById('userImgThumb').src = '';
    const old = document.getElementById('userChatImgPreview');
    if (old) old.remove();
}

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

// Gắn event cho input ảnh user
const userImageInput = document.getElementById('userImageInput');
if (userImageInput) {
    userImageInput.addEventListener('change', function() {
        previewUserImage(this);
    });
}

// Auto refresh every 5 seconds
setInterval(loadMessages, 5000);
</script>
@endauth
@endsection
