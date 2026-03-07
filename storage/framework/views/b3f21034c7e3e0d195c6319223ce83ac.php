<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi từ Fashion Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .reply-box {
            background: white;
            padding: 20px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
            border-radius: 5px;
        }
        .original-message {
            background: #e9ecef;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 14px;
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fashion Shop</h1>
        <p>Phản hồi từ chúng tôi</p>
    </div>

    <div class="content">
        <p>Xin chào <strong><?php echo e($contact->name); ?></strong>,</p>
        
        <p>Cảm ơn bạn đã liên hệ với chúng tôi. Dưới đây là phản hồi của chúng tôi về yêu cầu của bạn:</p>

        <div class="reply-box">
            <h3 style="margin-top: 0; color: #667eea;">📧 Phản hồi của chúng tôi:</h3>
            <p style="white-space: pre-line;"><?php echo e($contact->admin_reply); ?></p>
        </div>

        <div class="original-message">
            <h4 style="margin-top: 0;">Tin nhắn gốc của bạn:</h4>
            <p><strong>Chủ đề:</strong> <?php echo e($contact->subject); ?></p>
            <p><strong>Nội dung:</strong></p>
            <p style="white-space: pre-line;"><?php echo e($contact->message); ?></p>
            <p style="font-size: 12px; color: #6c757d; margin-bottom: 0;">
                Gửi lúc: <?php echo e($contact->created_at->format('H:i d/m/Y')); ?>

            </p>
        </div>

        <p>Nếu bạn có thêm câu hỏi, vui lòng liên hệ lại với chúng tôi hoặc truy cập website:</p>
        
        <div style="text-align: center;">
            <a href="<?php echo e(url('/')); ?>" class="btn">Truy cập Website</a>
        </div>

        <p style="margin-top: 20px;">Trân trọng,<br><strong>Đội ngũ Fashion Shop</strong></p>
    </div>

    <div class="footer">
        <p>Email: <?php echo e($contact->email); ?></p>
        <p>© <?php echo e(date('Y')); ?> Fashion Shop. All rights reserved.</p>
        <p style="font-size: 11px; margin-top: 10px;">
            Email này được gửi tự động, vui lòng không trả lời trực tiếp email này.
        </p>
    </div>
</body>
</html>
<?php /**PATH D:\Boutique\fashion-shop\resources\views/emails/contact-reply.blade.php ENDPATH**/ ?>