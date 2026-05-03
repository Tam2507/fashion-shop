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

#adminEditor:empty:before {
    content: attr(data-placeholder);
    color: #aaa;
    pointer-events: none;
}

#adminEditor img {
    max-width: 200px;
    max-height: 160px;
    border-radius: 10px;
    display: block;
    margin: 4px 0;
    border: 2px solid #0084ff44;
}
</style>
@endsection

@section('content')
<!-- Chat Header -->
<div class="chat-header">
    @if($firstMessage->user && $firstMessage->user->avatar)
        <img src="{{ \App\Services\ImageUploadService::url($firstMessage->user->avatar) }}" 
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
                    <img src="{{ \App\Services\ImageUploadService::url($message->user->avatar) }}" 
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
                    @if($message->image)
                        <img src="{{ \App\Services\ImageUploadService::url($message->image) }}" 
                             alt="Ảnh" 
                             style="max-width:220px; max-height:220px; border-radius:12px; display:block; cursor:pointer;"
                             onclick="window.open(this.src,'_blank')">
                    @endif
                    @if($message->message)
                        <span style="{{ $message->image ? 'display:block; margin-top:6px;' : '' }}">{{ $message->message }}</span>
                    @endif
                </div>
                <div class="message-time">
                    {{ $message->created_at->format('H:i') }}
                </div>
            </div>

            @if($isMyMessage)
                @if(auth()->user()->avatar)
                    <img src="{{ \App\Services\ImageUploadService::url(auth()->user()->avatar) }}" 
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
    <form id="adminReplyForm" action="{{ route('admin.messages.reply', $firstMessage->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" id="adminImageInput" name="image" accept="image/*" style="display:none;">
        <div class="chat-input-form">
            <label for="adminImageInput" style="cursor:pointer;color:#0084ff;padding:6px;flex-shrink:0;display:flex;align-items:center;" title="Chọn ảnh">
                <i class="fas fa-image" style="font-size:20px;"></i>
            </label>
            <div style="flex:1;border:1px solid #ccd0d5;border-radius:20px;background:#f0f2f5;overflow:hidden;">
                <textarea id="adminMsgInput" name="message" placeholder="Aa" rows="1"
                          style="border:none;background:transparent;padding:10px 16px;
                                 resize:none;font-size:15px;max-height:100px;
                                 outline:none;font-family:inherit;width:100%;display:block;"></textarea>
            </div>
            <button type="submit" style="width:36px;height:36px;border-radius:50%;border:none;
                    background:#0084ff;color:white;display:flex;align-items:center;
                    justify-content:center;cursor:pointer;flex-shrink:0;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var adminImageInput = document.getElementById('adminImageInput');
    var adminMsgInput   = document.getElementById('adminMsgInput');
    var adminReplyForm  = document.getElementById('adminReplyForm');
    var cm              = document.getElementById('chatMessages');

    // Scroll to bottom
    if (cm) cm.scrollTop = cm.scrollHeight;

    // ID của bubble preview tạm (chưa gửi)
    var previewBubbleId = null;

    // Khi chọn ảnh → hiện preview trong chat như bubble "đang soạn"
    adminImageInput.addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;

        // Xóa bubble preview cũ nếu có
        removePreviewBubble();

        var reader = new FileReader();
        reader.onload = function(e) {
            previewBubbleId = 'preview-bubble-' + Date.now();
            cm.insertAdjacentHTML('beforeend',
                '<div class="message-group my-message" id="'+previewBubbleId+'" style="opacity:0.6;">'
                +'<div class="message-content">'
                +'<div class="message-bubble" style="position:relative;">'
                +'<img src="'+e.target.result+'" style="max-width:220px;max-height:220px;border-radius:12px;display:block;">'
                +'<button onclick="cancelPreview()" type="button" '
                +'style="position:absolute;top:-8px;right:-8px;background:#333;border:none;color:white;'
                +'border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;'
                +'display:flex;align-items:center;justify-content:center;line-height:1;">✕</button>'
                +'</div>'
                +'<div class="message-time" style="font-style:italic;">Chưa gửi...</div>'
                +'</div>'
                +'<div class="message-avatar-placeholder"><i class="fas fa-user-shield"></i></div>'
                +'</div>');
            cm.scrollTop = cm.scrollHeight;
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Hủy ảnh đã chọn
    window.cancelPreview = function() {
        adminImageInput.value = '';
        removePreviewBubble();
    };

    function removePreviewBubble() {
        if (previewBubbleId) {
            var el = document.getElementById(previewBubbleId);
            if (el) el.remove();
            previewBubbleId = null;
        }
    }

    // Auto resize textarea
    adminMsgInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    adminMsgInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            doSend();
        }
    });

    adminReplyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        doSend();
    });

    async function doSend() {
        var msg     = adminMsgInput.value.trim();
        var imgFile = adminImageInput.files && adminImageInput.files[0];
        if (!msg && !imgFile) return;

        // Lấy src preview trước khi xóa bubble
        var previewSrc = '';
        if (previewBubbleId) {
            var previewEl = document.getElementById(previewBubbleId);
            if (previewEl) {
                var previewImg = previewEl.querySelector('img');
                if (previewImg) previewSrc = previewImg.src;
            }
        }

        // Xóa bubble preview tạm
        removePreviewBubble();

        // Build FormData thủ công
        var fd = new FormData();
        fd.append('_token', document.querySelector('input[name="_token"]').value);
        if (msg)     fd.append('message', msg);
        if (imgFile) fd.append('image', imgFile);

        // Reset input ngay
        adminMsgInput.value = '';
        adminMsgInput.style.height = 'auto';
        adminImageInput.value = '';

        try {
            var res  = await fetch(adminReplyForm.action, {
                method: 'POST',
                body: fd,
                headers: { 'Accept': 'application/json' }
            });
            var data = await res.json();

            if (data.success) {
                // Dùng URL thật từ server (Cloudinary), fallback về preview local
                var imgSrc  = data.image_url || (previewSrc.length > 10 ? previewSrc : '');
                var imgHtml = imgSrc ? '<img src="'+imgSrc+'" style="max-width:220px;max-height:220px;border-radius:12px;display:block;cursor:pointer;" onclick="window.open(this.src,\'_blank\')">' : '';
                var txtHtml = msg   ? '<span style="'+(imgSrc?'display:block;margin-top:6px;':'')+'">'+escapeHtml(msg)+'</span>' : '';

                var now = new Date();
                var t   = now.getHours().toString().padStart(2,'0')+':'+now.getMinutes().toString().padStart(2,'0');
                cm.insertAdjacentHTML('beforeend',
                    '<div class="message-group my-message">'
                    +'<div class="message-content"><div class="message-bubble">'+imgHtml+txtHtml+'</div>'
                    +'<div class="message-time">'+t+'</div></div>'
                    +'<div class="message-avatar-placeholder"><i class="fas fa-user-shield"></i></div>'
                    +'</div>');
                cm.scrollTop = cm.scrollHeight;
            } else {
                alert(data.error || 'Gửi thất bại');
            }
        } catch(err) {
            alert('Lỗi: ' + err.message);
        }
    }

    function escapeHtml(text) {
        var d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }
});
</script>

@endsection

@section('scripts')
@endsection