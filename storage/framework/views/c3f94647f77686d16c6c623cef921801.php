<?php if(!auth()->check() || !auth()->user()->is_admin): ?>
<!-- Floating Chat Button -->
<div id="chat-widget">
    <button id="chat-toggle" class="chat-toggle-btn">
        <i class="fas fa-comments"></i>
        <span id="unread-badge" class="badge bg-danger" style="display: none;">0</span>
    </button>

    <div id="chat-box" class="chat-box" style="display: none;">
        <div class="chat-header">
            <h6 class="mb-0"><i class="fas fa-headset"></i> Hỗ trợ khách hàng</h6>
            <button id="chat-close" class="btn-close btn-close-white"></button>
        </div>
        
        <div id="chat-messages" class="chat-messages">
            <div class="text-center text-muted py-3">
                <i class="fas fa-comments fa-2x mb-2"></i>
                <p>Xin chào! Chúng tôi có thể giúp gì cho bạn?</p>
            </div>
        </div>

        <div class="chat-input">
            <?php if(auth()->guard()->guest()): ?>
            <div id="guest-info" class="mb-2">
                <input type="text" id="guest-name" class="form-control form-control-sm mb-1" placeholder="Tên của bạn" required>
                <input type="email" id="guest-email" class="form-control form-control-sm" placeholder="Email của bạn" required>
            </div>
            <?php endif; ?>
            
            <div class="input-group">
                <input type="text" id="chat-message-input" class="form-control" placeholder="Nhập tin nhắn...">
                <button id="chat-send-btn" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

.chat-toggle-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    cursor: pointer;
    transition: transform 0.3s;
    position: relative;
}

.chat-toggle-btn:hover {
    transform: scale(1.1);
}

.chat-toggle-btn .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 10px;
    padding: 4px 6px;
}

.chat-box {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
}

.message {
    margin-bottom: 12px;
    animation: slideIn 0.3s;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message.user {
    text-align: right;
}

.message.admin {
    text-align: left;
}

.message-bubble {
    display: inline-block;
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 18px;
    word-wrap: break-word;
}

.message.user .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message.admin .message-bubble {
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
}

.message-time {
    font-size: 11px;
    color: #999;
    margin-top: 4px;
}

.chat-input {
    padding: 15px;
    background: white;
    border-top: 1px solid #e0e0e0;
}

@media (max-width: 576px) {
    .chat-box {
        width: calc(100vw - 40px);
        height: 400px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('chat-toggle');
    const chatBox = document.getElementById('chat-box');
    const chatClose = document.getElementById('chat-close');
    const chatMessages = document.getElementById('chat-messages');
    const messageInput = document.getElementById('chat-message-input');
    const sendBtn = document.getElementById('chat-send-btn');
    const guestName = document.getElementById('guest-name');
    const guestEmail = document.getElementById('guest-email');

    let guestEmailStored = localStorage.getItem('guest_email');

    // Toggle chat box
    chatToggle.addEventListener('click', function() {
        chatBox.style.display = chatBox.style.display === 'none' ? 'flex' : 'none';
        if (chatBox.style.display === 'flex') {
            loadMessages();
        }
    });

    chatClose.addEventListener('click', function() {
        chatBox.style.display = 'none';
    });

    // Send message
    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        <?php if(auth()->guard()->guest()): ?>
        const name = guestName.value.trim();
        const email = guestEmail.value.trim();
        
        if (!name || !email) {
            alert('Vui lòng nhập tên và email của bạn');
            return;
        }

        if (!validateEmail(email)) {
            alert('Email không hợp lệ');
            return;
        }

        guestEmailStored = email;
        localStorage.setItem('guest_email', email);
        <?php endif; ?>

        const data = {
            message: message,
            <?php if(auth()->guard()->guest()): ?>
            guest_name: name,
            guest_email: email,
            <?php endif; ?>
        };

        fetch('<?php echo e(route("messages.store")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                addMessageToUI(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function loadMessages() {
        let url = '<?php echo e(route("messages.index")); ?>';
        
        <?php if(auth()->guard()->guest()): ?>
        if (guestEmailStored) {
            url += '?email=' + encodeURIComponent(guestEmailStored);
        }
        <?php endif; ?>

        fetch(url)
            .then(response => response.json())
            .then(data => {
                chatMessages.innerHTML = '';
                if (data.messages.length === 0) {
                    chatMessages.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-comments fa-2x mb-2"></i>
                            <p>Xin chào! Chúng tôi có thể giúp gì cho bạn?</p>
                        </div>
                    `;
                } else {
                    data.messages.forEach(msg => addMessageToUI(msg));
                }
            });
    }

    function addMessageToUI(msg) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + (msg.is_admin_reply ? 'admin' : 'user');
        
        const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });

        messageDiv.innerHTML = `
            <div class="message-bubble">${escapeHtml(msg.message)}</div>
            <div class="message-time">${time}</div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto-refresh messages every 10 seconds
    setInterval(function() {
        if (chatBox.style.display === 'flex') {
            loadMessages();
        }
    }, 10000);
});
</script>
<?php endif; ?>
<?php /**PATH D:\Boutique\fashion-shop\resources\views/components/chat-box.blade.php ENDPATH**/ ?>