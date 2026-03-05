@extends('layouts.app')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-shopping-cart"></i> Quản Lý Đơn Hàng</h1>
</div>

<!-- Filter by Status -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Lọc theo trạng thái</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả đơn hàng</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Đã nhận</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Đơn mới</h5>
                <h2 class="text-warning">{{ \App\Models\Order::where('status', 'received')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Đang xử lý</h5>
                <h2 class="text-info">{{ \App\Models\Order::whereIn('status', ['processing', 'confirmed'])->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Đã giao</h5>
                <h2 class="text-success">{{ \App\Models\Order::where('status', 'delivered')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Doanh thu</h5>
                <h2 class="text-primary">{{ number_format(\App\Models\Order::where('status', 'delivered')->sum('total_price'), 0, ',', '.') }}đ</h2>
                <small class="text-muted">Chỉ tính đơn đã giao</small>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->user->name ?? 'Khách' }}</td>
                        <td>{{ $order->phone ?? '-' }}</td>
                        <td><strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></td>
                        <td>
                            <span class="badge bg-{{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đơn hàng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endsection
