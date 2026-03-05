<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Giao Hàng #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            background: white;
        }
        
        .shipping-label {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .provider-logo {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
        }
        
        .provider-logo.ghn { color: #e74c3c; }
        .provider-logo.jnt { color: #d32f2f; }
        .provider-logo.shopee { color: #ee4d2d; }
        .provider-logo.viettel { color: #0066cc; }
        .provider-logo.vnpost { color: #006633; }
        
        .order-code {
            font-size: 20px;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            color: #333;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .products-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .total-section {
            text-align: right;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .barcode {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            border: 2px dashed #000;
        }
        
        .barcode-number {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #000;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            .shipping-label {
                border: 2px solid #000;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 30px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ In phiếu
    </button>

    <div class="shipping-label">
        <!-- Header -->
        <div class="header">
            <div>
                <div class="provider-logo {{ $shippingProvider }}">
                    @switch($shippingProvider)
                        @case('ghn')
                            GIAO HÀNG NHANH
                            @break
                        @case('jnt')
                            J&T EXPRESS
                            @break
                        @case('shopee')
                            SHOPEE EXPRESS
                            @break
                        @case('viettel')
                            VIETTEL POST
                            @break
                        @case('vnpost')
                            VNPOST
                            @break
                        @default
                            GIAO HÀNG NHANH
                    @endswitch
                </div>
                <div style="margin-top: 5px; font-size: 12px;">Hotline: 1900-xxxx</div>
            </div>
            <div class="order-code">
                ĐƠN HÀNG #{{ $order->id }}
            </div>
        </div>

        <!-- Barcode -->
        <div class="barcode">
            <div class="barcode-number">{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}</div>
            <div style="margin-top: 10px; font-size: 12px;">Mã vận đơn</div>
        </div>

        <!-- Sender Information -->
        <div class="section">
            <div class="section-title">📦 Thông tin người gửi</div>
            @php
                $footerSettings = \App\Models\FooterSetting::first();
            @endphp
            <div class="info-row">
                <div class="info-label">Tên:</div>
                <div class="info-value">{{ $footerSettings->company_name ?? 'Fashion Shop' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Địa chỉ:</div>
                <div class="info-value">{{ $footerSettings->address ?? 'Cẩm Phả - Quảng Ninh' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value">{{ $footerSettings->phone ?? '0388567952' }}</div>
            </div>
        </div>

        <!-- Receiver Information -->
        <div class="section">
            <div class="section-title">👤 Thông tin người nhận</div>
            <div class="info-row">
                <div class="info-label">Tên:</div>
                <div class="info-value"><strong>{{ $order->user->name ?? 'Khách hàng' }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Số điện thoại:</div>
                <div class="info-value"><strong>{{ $order->phone ?? '-' }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Địa chỉ:</div>
                <div class="info-value">
                    <strong>
                        @if(is_array($order->shipping_address))
                            {{ $order->shipping_address['address'] ?? '' }}, 
                            {{ $order->shipping_address['district'] ?? '' }}, 
                            {{ $order->shipping_address['city'] ?? '' }}
                        @else
                            {{ $order->shipping_address }}
                        @endif
                    </strong>
                </div>
            </div>
            @if($order->notes)
            <div class="info-row">
                <div class="info-label">Ghi chú:</div>
                <div class="info-value">{{ $order->notes }}</div>
            </div>
            @endif
        </div>

        <!-- Products -->
        <div class="section">
            <div class="section-title">📋 Danh sách sản phẩm</div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Biến thể</th>
                        <th style="text-align: center;">SL</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'Sản phẩm' }}</td>
                        <td>
                            @if($item->variant)
                                {{ $item->variant->size ?? '' }} 
                                @if($item->variant->color)
                                    - {{ $item->variant->color }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                        <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div style="margin-bottom: 5px;">
                    Tạm tính: {{ number_format($order->total_price, 0, ',', '.') }}đ
                </div>
                @if($order->discount_amount > 0)
                <div style="margin-bottom: 5px; color: #e74c3c;">
                    Giảm giá: -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                </div>
                @endif
                @if($order->shipping_cost > 0)
                <div style="margin-bottom: 5px;">
                    Phí vận chuyển: {{ number_format($order->shipping_cost, 0, ',', '.') }}đ
                </div>
                @endif
                <div style="font-size: 22px; color: #e74c3c; margin-top: 10px;">
                    TỔNG CỘNG: {{ number_format($order->final_total, 0, ',', '.') }}đ
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="section">
            <div class="section-title">💰 Thông tin thanh toán</div>
            <div class="info-row">
                <div class="info-label">Hình thức:</div>
                <div class="info-value">
                    <strong>
                        @if($order->paymentMethod)
                            {{ $order->paymentMethod->name }}
                        @else
                            Thu tiền khi giao hàng (COD)
                        @endif
                    </strong>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Trạng thái:</div>
                <div class="info-value">
                    <strong>
                        ⏳ Chưa thanh toán - Thu tiền người nhận
                    </strong>
                </div>
            </div>
        </div>

        <!-- Footer with Signatures -->
        <div class="footer">
            <div class="signature-box">
                <div><strong>Người gửi</strong></div>
                <div style="font-size: 12px; margin-top: 5px;">(Ký, ghi rõ họ tên)</div>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <div><strong>Người nhận</strong></div>
                <div style="font-size: 12px; margin-top: 5px;">(Ký, ghi rõ họ tên)</div>
                <div class="signature-line"></div>
            </div>
        </div>

        <!-- Print Info -->
        <div style="margin-top: 20px; text-align: center; font-size: 11px; color: #666;">
            Ngày in: {{ now()->format('d/m/Y H:i:s') }} | Đơn hàng #{{ $order->id }} | {{ $order->status_label }}
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
