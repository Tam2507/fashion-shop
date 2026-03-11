<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã Giảm Giá Đặc Biệt</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .coupon-box {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .coupon-code {
            background: white;
            color: #f5576c;
            font-size: 32px;
            font-weight: bold;
            padding: 15px 30px;
            border-radius: 5px;
            display: inline-block;
            letter-spacing: 3px;
            margin: 10px 0;
            border: 2px dashed #f5576c;
        }
        .coupon-details {
            color: white;
            font-size: 14px;
            margin-top: 15px;
        }
        .stats {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .stats-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .stats-item:last-child {
            border-bottom: none;
        }
        .stats-label {
            font-weight: 600;
            color: #666;
        }
        .stats-value {
            color: #667eea;
            font-weight: bold;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: transform 0.3s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Chúc Mừng!</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Bạn Nhận Được Mã Giảm Giá Đặc Biệt</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Xin chào <strong>{{ $user->name }}</strong>,
            </div>
            
            <div class="message">
                Cảm ơn bạn đã tin tưởng và ủng hộ cửa hàng chúng tôi! 💝
                <br><br>
                Chúng tôi rất vui mừng thông báo rằng bạn đã đạt được mốc chi tiêu đặc biệt và nhận được phần thưởng xứng đáng!
            </div>

            <div class="stats">
                <div class="stats-item">
                    <span class="stats-label">Tổng chi tiêu của bạn:</span>
                    <span class="stats-value">{{ number_format($totalSpent, 0, ',', '.') }}đ</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Mức giảm giá:</span>
                    <span class="stats-value">{{ $coupon->value }}%</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Hiệu lực đến:</span>
                    <span class="stats-value">{{ $coupon->expires_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="coupon-box">
                <div style="color: white; font-size: 18px; margin-bottom: 10px;">
                    MÃ GIẢM GIÁ CỦA BẠN
                </div>
                <div class="coupon-code">
                    {{ $coupon->code }}
                </div>
                <div class="coupon-details">
                    Giảm {{ $coupon->value }}% cho đơn hàng tiếp theo
                    @if($coupon->minimum_amount)
                    <br>Áp dụng cho đơn hàng từ {{ number_format($coupon->minimum_amount, 0, ',', '.') }}đ
                    @endif
                    @if($coupon->maximum_discount)
                    <br>Giảm tối đa {{ number_format($coupon->maximum_discount, 0, ',', '.') }}đ
                    @endif
                </div>
            </div>

            <div class="note">
                <strong>📌 Lưu ý:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Mã giảm giá có hiệu lực đến {{ $coupon->expires_at->format('d/m/Y H:i') }}</li>
                    <li>Mỗi khách hàng chỉ được sử dụng mã này 1 lần</li>
                    <li>Không áp dụng đồng thời với các chương trình khuyến mãi khác</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/products') }}" class="cta-button">
                    🛍️ Mua Sắm Ngay
                </a>
            </div>

            <div class="message" style="margin-top: 30px; font-size: 14px; color: #666;">
                Một lần nữa, cảm ơn bạn đã đồng hành cùng chúng tôi. Chúng tôi hy vọng sẽ tiếp tục được phục vụ bạn!
                <br><br>
                Trân trọng,<br>
                <strong>Fashion Shop Team</strong>
            </div>
        </div>
        
        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
            <p>© {{ date('Y') }} Fashion Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
