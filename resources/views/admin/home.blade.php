@extends('layouts.admin')

@section('page_title', 'Dashboard Quản Lý')
@section('header_icon', 'fas fa-tachometer-alt')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tổng Sản Phẩm</div>
                            <div class="h5 mb-0 font-weight-bold">{{ \App\Models\Product::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Đơn Hàng Hôm Nay</div>
                            <div class="h5 mb-0 font-weight-bold">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Doanh Thu Tháng</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format(\App\Models\Order::whereMonth('created_at', now()->month)->sum('total_price'), 0, ',', '.') }}đ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Khách Hàng</div>
                            <div class="h5 mb-0 font-weight-bold">{{ \App\Models\User::where('is_admin', false)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao Tác Nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-tags me-2"></i>Thêm Danh Mục
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-list me-2"></i>Quản Lý Đơn Hàng
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-user-cog me-2"></i>Quản Lý User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="row">
        <!-- Product Management -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Quản Lý Sản Phẩm</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Xem Tất Cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Giá</th>
                                    <th>Tồn Kho</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Product::with('variants')->latest()->limit(5)->get() as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="rounded me-2" width="40" height="40">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ Str::limit($product->name, 30) }}</div>
                                                <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                    <td>
                                        @php 
                                            $totalStock = $product->variants->count() > 0 
                                                ? $product->variants->sum('stock_quantity') 
                                                : $product->quantity;
                                        @endphp
                                        <span class="badge bg-{{ $totalStock > 10 ? 'success' : ($totalStock > 0 ? 'warning' : 'danger') }}">
                                            {{ $totalStock }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Xác nhận xóa?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Management -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Đơn Hàng Gần Đây</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Xem Tất Cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Order::with('user')->latest()->limit(5)->get() as $order)
                                <tr>
                                    <td>
                                        <strong>ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                        <br><small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        {{ $order->user->name ?? $order->guest_email ?? 'Khách vãng lai' }}
                                    </td>
                                    <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Biểu Đồ Doanh Thu</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Trạng Thái Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Hoạt Động Gần Đây</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach(\App\Models\Order::with('user')->latest()->limit(10)->get() as $order)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Đơn hàng mới #{{ $order->id }}</h6>
                                <p class="timeline-text">
                                    Khách hàng {{ $order->user->name ?? 'Khách vãng lai' }} đã đặt đơn hàng 
                                    trị giá {{ number_format($order->total_price, 0, ',', '.') }}đ
                                </p>
                                <span class="timeline-time">{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-block {
    width: 100%;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -31px;
    top: 15px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #dee2e6;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
    color: #6c757d;
}

.timeline-time {
    font-size: 12px;
    color: #adb5bd;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: none;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.text-xs {
    font-size: 0.7rem;
}

.text-gray-300 {
    color: rgba(255, 255, 255, 0.3) !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: [
                {{ \App\Models\Order::whereMonth('created_at', 1)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 2)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 3)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 4)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 5)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 6)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 7)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 8)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 9)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 10)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 11)->sum('total_price') }},
                {{ \App\Models\Order::whereMonth('created_at', 12)->sum('total_price') }}
            ],
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
                    }
                }
            }
        }
    }
});

// Order Status Chart
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusChart = new Chart(orderStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Đang xử lý', 'Đã xác nhận', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [
                {{ \App\Models\Order::where('status', 'processing')->count() }},
                {{ \App\Models\Order::where('status', 'confirmed')->count() }},
                {{ \App\Models\Order::where('status', 'delivered')->count() }},
                {{ \App\Models\Order::where('status', 'cancelled')->count() }}
            ],
            backgroundColor: [
                '#f6c23e',
                '#1cc88a',
                '#28a745',
                '#e74a3b'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const value = context.parsed;
                        const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return context.label + ': ' + value + ' (' + pct + '%)';
                    }
                }
            },
            datalabels: {
                color: '#fff',
                font: { weight: 'bold', size: 13 },
                formatter: function(value, context) {
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    if (total === 0 || value === 0) return '';
                    return ((value / total) * 100).toFixed(1) + '%';
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});
</script>
@endsection