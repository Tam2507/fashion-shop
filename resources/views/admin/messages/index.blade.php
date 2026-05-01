@extends('layouts.messenger')

@section('title', 'Tin Nhắn Khách Hàng')

@section('styles')
<style>
.messenger-wrapper {
    height: 100%;
    background: #fff;
}

.messenger-container {
    height: 100%;
    display: flex;
    background: #fff;
}

/* Sidebar */
.conversations-sidebar {
    width: 360px;
    border-right: 1px solid #e4e6eb;
    display: flex;
    flex-direction: column;
    background: #fff;
}

.conversations-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e4e6eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.conversations-header h5 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #050505;
}

.conversations-search {
    padding: 8px 16px;
}

.conversations-search input {
    width: 100%;
    padding: 10px 16px;
    border: none;
    border-radius: 20px;
    background: #f0f2f5;
    font-size: 15px;
}

.conversations-search input:focus {
    outline: none;
    background: #e4e6eb;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
}

.conversation-item {
    padding: 8px 16px;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: inherit;
}

.conversation-item:hover {
    background: #f2f2f2;
}

.conversation-item.active {
    background: #e7f3ff;
}

.conversation-item.unread {
    background: #f0f8ff;
}

.conversation-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.conversation-avatar-placeholder {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.conversation-info {
    flex: 1;
    min-width: 0;
}

.conversation-name {
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 4px;
}

.conversation-preview {
    font-size: 13px;
    color: #65676b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
    flex-shrink: 0;
}

.conversation-time {
    font-size: 12px;
    color: #65676b;
}

.unread-badge {
    width: 16px;
    height: 16px;
    background: #0084ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 11px;
    font-weight: 600;
}

/* Chat Area */
.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
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
    font-size: 12px;
}

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

.empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #65676b;
    padding: 40px;
}

.empty-state i {
    font-size: 100px;
    margin-bottom: 20px;
    color: #e4e6eb;
}

.empty-state h4 {
    margin-bottom: 8px;
    color: #050505;
}

.empty-state p {
    color: #65676b;
    font-size: 14px;
}

/* Scrollbar */
.conversations-list::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar {
    width: 8px;
}

.conversations-list::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track {
    background: transparent;
}

.conversations-list::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb {
    background: #ccd0d5;
    border-radius: 4px;
}

.conversations-list::-webkit-scrollbar-thumb:hover,
.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #b0b3b8;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .conversations-sidebar {
        width: 100%;
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 10;
        transition: transform 0.3s ease;
    }

    .conversations-sidebar.hidden-mobile {
        transform: translateX(-100%);
        pointer-events: none;
    }

    .chat-area {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 5;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }

    .chat-area.visible-mobile {
        transform: translateX(0);
        z-index: 15;
    }

    .messenger-container {
        position: relative;
        overflow: hidden;
    }

    .btn-back-mobile {
        display: flex !important;
    }
}

.btn-back-mobile {
    display: none;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    color: #0084ff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 8px;
}
</style>
@endsection

@section('content')
<div class="messenger-wrapper">
    <div class="messenger-container">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="conversations-header">
                <h5>Chats</h5>
            </div>

            <div class="conversations-search">
                <input type="text" id="searchConversations" placeholder="Tìm kiếm trong Messenger" onkeyup="filterConversations()">
            </div>

            <div class="conversations-list" id="conversationsList">
                @php
                    $conversations = [];
                    foreach($messages as $msg) {
                        $key = $msg->user_id ?: $msg->guest_email;
                        if (!isset($conversations[$key])) {
                            $conversations[$key] = $msg;
                        }
                    }
                @endphp

                @forelse($conversations as $conversation)
                    <div class="conversation-item" 
                         data-id="{{ $conversation->id }}"
                         data-user-id="{{ $conversation->user_id }}"
                         data-guest-email="{{ $conversation->guest_email }}"
                         data-name="{{ $conversation->user ? $conversation->user->name : $conversation->guest_name }}"
                         data-email="{{ $conversation->user ? $conversation->user->email : $conversation->guest_email }}"
                         data-avatar="{{ $conversation->user && $conversation->user->avatar ? '/storage/' . $conversation->user->avatar : '' }}"
                         data-message="{{ $conversation->message }}"
                         onclick="loadConversation(this)">
                        @if($conversation->user && $conversation->user->avatar)
                            <img src="{{ \App\Services\ImageUploadService::url($conversation->user->avatar) }}" 
                                 alt="{{ $conversation->user->name }}" 
                                 class="conversation-avatar">
                        @else
                            <div class="conversation-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif

                        <div class="conversation-info">
                            <div class="conversation-name">
                                {{ $conversation->user ? $conversation->user->name : $conversation->guest_name }}
                            </div>
                            <div class="conversation-preview">
                                {{ Str::limit($conversation->message, 35) }}
                            </div>
                        </div>

                        <div class="conversation-meta">
                            <div class="conversation-time">
                                {{ $conversation->created_at->diffForHumans(null, true) }}
                            </div>
                            @if(!$conversation->is_read && !$conversation->is_admin_reply)
                                <div class="unread-badge">●</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có tin nhắn nào</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area" id="chatArea">
            <div class="empty-state">
                <i class="fab fa-facebook-messenger"></i>
                <h4>Tin nhắn của bạn</h4>
                <p>Chọn một cuộc trò chuyện để bắt đầu nhắn tin</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentConversationId = null;

function loadConversation(element) {
    // Remove active class from all conversations
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to clicked conversation
    element.classList.add('active');
    
    const conversationId = element.dataset.id;
    currentConversationId = conversationId;
    
    // Load messages
    fetch(`/admin/messages/${conversationId}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const chatHeader = doc.querySelector('.chat-header');
            const chatMessages = doc.querySelector('.chat-messages');
            const chatInput = doc.querySelector('.chat-input');
            
            if (chatHeader && chatMessages && chatInput) {
                // Thêm nút back mobile vào header
                const backBtn = document.createElement('button');
                backBtn.className = 'btn-back-mobile';
                backBtn.innerHTML = '<i class="fas fa-arrow-left"></i>';
                backBtn.onclick = showSidebar;
                chatHeader.insertBefore(backBtn, chatHeader.firstChild);

                document.getElementById('chatArea').innerHTML = `
                    ${chatHeader.outerHTML}
                    ${chatMessages.outerHTML}
                    ${chatInput.outerHTML}
                `;

                // Mobile: ẩn sidebar, hiện chat
                if (window.innerWidth <= 768) {
                    document.querySelector('.conversations-sidebar').classList.add('hidden-mobile');
                    document.getElementById('chatArea').classList.add('visible-mobile');
                    // Gắn lại sự kiện cho nút back (vì innerHTML đã replace)
                    const btn = document.querySelector('.btn-back-mobile');
                    if (btn) btn.onclick = showSidebar;
                }
                
                scrollToBottom();
                
                const form = document.querySelector('#chatArea form');
                if (form) {
                    form.addEventListener('submit', handleMessageSubmit);
                }
                
                const textarea = document.querySelector('#chatArea textarea');
                if (textarea) {
                    textarea.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
                    });
                    textarea.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            form.dispatchEvent(new Event('submit'));
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error loading conversation:', error);
        });
}

function showSidebar() {
    document.querySelector('.conversations-sidebar').classList.remove('hidden-mobile');
    document.getElementById('chatArea').classList.remove('visible-mobile');
}

function handleMessageSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const textarea = form.querySelector('textarea');
    const message = textarea.value.trim();
    
    if (!message) return;
    
    // Disable form
    textarea.disabled = true;
    form.querySelector('button').disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear textarea
            textarea.value = '';
            textarea.style.height = 'auto';
            
            // Reload conversation
            const activeConv = document.querySelector('.conversation-item.active');
            if (activeConv) {
                loadConversation(activeConv);
            }
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Có lỗi xảy ra khi gửi tin nhắn');
    })
    .finally(() => {
        textarea.disabled = false;
        form.querySelector('button').disabled = false;
        textarea.focus();
    });
}

function scrollToBottom() {
    const chatMessages = document.querySelector('#chatArea .chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

function filterConversations() {
    const searchInput = document.getElementById('searchConversations').value.toLowerCase();
    const conversations = document.querySelectorAll('.conversation-item');
    
    conversations.forEach(conv => {
        const name = conv.getAttribute('data-name').toLowerCase();
        const message = conv.getAttribute('data-message').toLowerCase();
        
        if (name.includes(searchInput) || message.includes(searchInput)) {
            conv.style.display = 'flex';
        } else {
            conv.style.display = 'none';
        }
    });
}

// Auto refresh every 10 seconds
setInterval(() => {
    if (currentConversationId) {
        const activeConv = document.querySelector('.conversation-item.active');
        if (activeConv) {
            loadConversation(activeConv);
        }
    }
}, 10000);
</script>
@endsection
