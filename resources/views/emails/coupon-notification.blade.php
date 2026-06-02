<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã Giảm Giá Mới</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:#8B3A3A;padding:30px;text-align:center;">
                            <h1 style="color:#fff;margin:0;font-size:26px;">🎁 Fashion Shop</h1>
                            <p style="color:#f5c6c6;margin:8px 0 0;font-size:15px;">Ưu đãi đặc biệt dành riêng cho bạn</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px 40px;">
                            <p style="font-size:16px;color:#333;margin:0 0 16px;">Xin chào <strong>{{ $user->name }}</strong>,</p>
                            <p style="font-size:15px;color:#555;margin:0 0 24px;">Chúng tôi có một mã giảm giá đặc biệt dành cho bạn. Đừng bỏ lỡ!</p>

                            <!-- Coupon Box -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:#fff8f0;border:2px dashed #8B3A3A;border-radius:8px;padding:24px;text-align:center;">
                                        <p style="margin:0 0 8px;font-size:13px;color:#888;text-transform:uppercase;letter-spacing:1px;">Mã giảm giá của bạn</p>
                                        <p style="margin:0 0 12px;font-size:32px;font-weight:bold;color:#8B3A3A;letter-spacing:3px;">{{ $coupon->code }}</p>
                                        <p style="margin:0;font-size:16px;color:#333;">
                                            @if($coupon->type === 'percentage')
                                                Giảm <strong>{{ number_format($coupon->value) }}%</strong>
                                                @if($coupon->maximum_discount)
                                                    (tối đa {{ number_format($coupon->maximum_discount, 0, ',', '.') }}đ)
                                                @endif
                                            @elseif($coupon->type === 'free_shipping')
                                                <strong>Miễn phí vận chuyển</strong>
                                            @else
                                                Giảm <strong>{{ number_format($coupon->value, 0, ',', '.') }}đ</strong>
                                            @endif
                                        </p>
                                        @if($coupon->minimum_amount)
                                            <p style="margin:8px 0 0;font-size:13px;color:#888;">Đơn hàng tối thiểu {{ number_format($coupon->minimum_amount, 0, ',', '.') }}đ</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:14px;color:#888;margin:20px 0 0;text-align:center;">
                                Có hiệu lực từ {{ \Carbon\Carbon::parse($coupon->starts_at)->format('d/m/Y') }}
                                đến {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                            </p>

                            <div style="text-align:center;margin-top:28px;">
                                <a href="{{ config('app.url') }}/products"
                                   style="background:#8B3A3A;color:#fff;text-decoration:none;padding:14px 36px;border-radius:6px;font-size:16px;font-weight:bold;display:inline-block;">
                                    Mua sắm ngay
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9f9f9;padding:20px 40px;text-align:center;border-top:1px solid #eee;">
                            <p style="margin:0;font-size:13px;color:#aaa;">© {{ date('Y') }} Fashion Shop. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
