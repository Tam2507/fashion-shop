@extends('layouts.admin')

@section('title', 'Thống Kê Chi Tiết')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-chart-bar"></i> Thống Kê Chi Tiết</h1>
</div>

{{-- KPI --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">Tổng doanh thu</div>
            <div class="fw-bold fs-5 text-danger">{{ number_format($totalRevenue, 0, ',', '.') }}₫</div>
        </div>
    </div>
    @foreach($orderStats as $s)
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">{{ \App\Models\Order::make(['status'=>$s->status])->status_label }}</div>
            <div class="fw-bold fs-5">{{ $s->count }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    {{-- Biểu đồ doanh thu --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">Doanh Thu 12 Tháng Gần Nhất</div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Biểu đồ trạng thái đơn --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">Trạng Thái Đơn Hàng</div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Biểu đồ số đơn hàng --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">Số Đơn Hàng 12 Tháng</div>
            <div class="card-body">
                <canvas id="orderChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Biểu đồ khách hàng mới --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-bold">Khách Hàng Mới 6 Tháng</div>
            <div class="card-body">
                <canvas id="userChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Top sản phẩm --}}
<div class="card border-0 shadow-sm">
    <div class="card-header fw-bold"><i class="fas fa-fire text-danger me-2"></i>Top 5 Sản Phẩm Bán Chạy</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Đã bán</th>
                    <th>Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-danger">{{ $item->total_sold }}</span></td>
                    <td class="text-success fw-semibold">{{ number_format($item->total_revenue, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const months = @json($months);
const revenueData = @json($revenueData);
const orderCountData = @json($orderCountData);
const userMonths = @json($userMonths);
const newUsersData = @json($newUsersData);
const statusLabels = @json($orderStats->pluck('status')->map(fn($s) => match($s) {
    'received' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận',
    'shipping' => 'Đang giao', 'delivered' => 'Đã giao',
    'cancelled' => 'Đã hủy', default => $s
}));
const statusData = @json($orderStats->pluck('count'));

// Doanh thu
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: revenueData,
            borderColor: '#8B3A3A',
            backgroundColor: 'rgba(139,58,58,0.1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Trạng thái đơn
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{ data: statusData, backgroundColor: ['#ffc107','#0d6efd','#0dcaf0','#198754','#dc3545'] }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

// Số đơn hàng
new Chart(document.getElementById('orderChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{ label: 'Đơn hàng', data: orderCountData, backgroundColor: '#0d6efd' }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Khách hàng mới
new Chart(document.getElementById('userChart'), {
    type: 'bar',
    data: {
        labels: userMonths,
        datasets: [{ label: 'Khách mới', data: newUsersData, backgroundColor: '#198754' }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
@endsection
