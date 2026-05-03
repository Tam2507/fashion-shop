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
let previewBubbleId = null;

function loadConversation(element) {
    document.querySelectorAll('.conversation-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    const conversationId = element.dataset.id;
    currentConversationId = conversationId;

    fetch(`/admin/messages/${conversationId}`)
        .then(r => r.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const chatHeader   = doc.querySelector('.chat-header');
            const chatMessages = doc.querySelector('.chat-messages');
            const chatInput    = doc.querySelector('.chat-input');

            if (!chatHeader || !chatMessages || !chatInput) return;

            // Nút back mobile
            const backBtn = document.createElement('button');
            backBtn.className = 'btn-back-mobile';
            backBtn.innerHTML = '<i class="fas fa-arrow-left"></i>';
            backBtn.onclick = showSidebar;
            chatHeader.insertBefore(backBtn, chatHeader.firstChild);

            const chatArea = document.getElementById('chatArea');
            chatArea.innerHTML = chatHeader.outerHTML + chatMessages.outerHTML + chatInput.outerHTML;

            if (window.innerWidth <= 768) {
                document.querySelector('.conversations-sidebar').classList.add('hidden-mobile');
                chatArea.classList.add('visible-mobile');
                const btn = chatArea.querySelector('.btn-back-mobile');
                if (btn) btn.onclick = showSidebar;
            }

            scrollToBottom();
            initChatInput(conversationId);
        })
        .catch(err => console.error('Error loading conversation:', err));
}

function initChatInput(conversationId) {
    previewBubbleId = null;

    const form        = document.querySelector('#chatArea #adminReplyForm');
    const imgInput    = document.querySelector('#chatArea #adminImageInput');
    const msgInput    = document.querySelector('#chatArea #adminMsgInput');
    const cm          = document.querySelector('#chatArea .chat-messages');
    const previewBox  = document.querySelector('#chatArea #imgPreviewBox');
    const previewThumb= document.querySelector('#chatArea #imgPreviewThumb');

    if (!form || !imgInput || !msgInput || !cm) return;

    form.action = `/admin/messages/${conversationId}/reply`;

    // Preview ảnh trong ô soạn tin
    imgInput.addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;
        const objectUrl = URL.createObjectURL(this.files[0]);
        if (previewThumb) previewThumb.src = objectUrl;
        if (previewBox)   previewBox.style.display = 'flex';
    });

    // Nút xóa ảnh preview
    const removeBtn = document.querySelector('#chatArea #removeImgPreview');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            imgInput.value = '';
            if (previewBox)   previewBox.style.display = 'none';
            if (previewThumb) previewThumb.src = '';
        });
    }

    // Auto resize textarea
    msgInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    msgInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            doSend(form, imgInput, msgInput, cm, conversationId);
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        doSend(form, imgInput, msgInput, cm, conversationId);
    });
}

async function doSend(form, imgInput, msgInput, cm, conversationId) {
    const msg     = msgInput.value.trim();
    const imgFile = imgInput.files && imgInput.files[0];
    if (!msg && !imgFile) return;

    // Lấy preview src trước khi xóa
    let previewSrc = '';
    const previewThumb = document.querySelector('#chatArea #imgPreviewThumb');
    if (previewThumb && previewThumb.src) previewSrc = previewThumb.src;

    // Clear preview box
    const previewBox = document.querySelector('#chatArea #imgPreviewBox');
    if (previewBox) previewBox.style.display = 'none';
    if (previewThumb) previewThumb.src = '';
    removePreviewBubble(cm);

    const fd = new FormData();
    fd.append('_token', form.querySelector('input[name="_token"]').value);
    if (msg)     fd.append('message', msg);
    if (imgFile) fd.append('image', imgFile);

    msgInput.value = '';
    msgInput.style.height = 'auto';
    imgInput.value = '';

    try {
        const res  = await fetch(`/admin/messages/${conversationId}/reply`, {
            method: 'POST',
            body: fd,
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();

        if (data.success) {
            const imgSrc  = data.image_url || (previewSrc.length > 10 ? previewSrc : '');
            const imgHtml = imgSrc ? `<img src="${imgSrc}" style="max-width:220px;max-height:220px;border-radius:12px;display:block;cursor:pointer;" onclick="window.open(this.src,'_blank')">` : '';
            const txtHtml = msg   ? `<span style="${imgSrc ? 'display:block;margin-top:6px;' : ''}">${escapeHtml(msg)}</span>` : '';

            const now = new Date();
            const t   = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');
            const div = document.createElement('div');
            div.className = 'message-group my-message';
            div.innerHTML =
                '<div class="message-content"><div class="message-bubble">' + imgHtml + txtHtml + '</div>'
                + '<div class="message-time">' + t + '</div></div>'
                + '<div class="message-avatar-placeholder"><i class="fas fa-user-shield"></i></div>';
            cm.appendChild(div);
            cm.scrollTop = cm.scrollHeight;

            // Cập nhật preview trong sidebar
            const activeConv = document.querySelector('.conversation-item.active');
            if (activeConv && msg) {
                const preview = activeConv.querySelector('.conversation-preview');
                if (preview) preview.textContent = msg.substring(0, 35);
            }
        } else {
            alert(data.error || 'Gửi thất bại');
        }
    } catch(err) {
        alert('Lỗi: ' + err.message);
    }
}

function removePreviewBubble(cm) {
    if (previewBubbleId) {
        const el = document.getElementById(previewBubbleId);
        if (el) el.remove();
        previewBubbleId = null;
    }
}

function showSidebar() {
    document.querySelector('.conversations-sidebar').classList.remove('hidden-mobile');
    document.getElementById('chatArea').classList.remove('visible-mobile');
}

function scrollToBottom() {
    const chatMessages = document.querySelector('#chatArea .chat-messages');
    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
}

function filterConversations() {
    const q = document.getElementById('searchConversations').value.toLowerCase();
    document.querySelectorAll('.conversation-item').forEach(conv => {
        const name = (conv.dataset.name || '').toLowerCase();
        const msg  = (conv.dataset.message || '').toLowerCase();
        conv.style.display = (name.includes(q) || msg.includes(q)) ? 'flex' : 'none';
    });
}

function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

</script>
@endsection
