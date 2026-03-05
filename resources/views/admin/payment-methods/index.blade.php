@extends('layouts.admin')

@section('title', 'Quản Lý Phương Thức Thanh Toán')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-credit-card"></i> Quản Lý Phương Thức Thanh Toán</h1>
    <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Phương Thức
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($paymentMethods->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Tên</th>
                            <th>Mã</th>
                            <th>Vị trí</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $method)
                        <tr>
                            <td>
                                @if($method->logo)
                                    <img src="/storage/{{ $method->logo }}" alt="{{ $method->name }}" 
                                         style="height: 30px; max-width: 60px; object-fit: contain;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 30px; border-radius: 4px;">
                                        <i class="fas fa-credit-card text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $method->name }}</strong>
                                @if($method->description)
                                    <br><small class="text-muted">{{ Str::limit($method->description, 50) }}</small>
                                @endif
                            </td>
                            <td><code>{{ $method->code }}</code></td>
                            <td>{{ $method->position }}</td>
                            <td>
                                <button class="btn btn-sm btn-toggle {{ $method->is_active ? 'btn-success' : 'btn-secondary' }}" 
                                        onclick="toggleStatus({{ $method->id }}, this)">
                                    <i class="fas fa-{{ $method->is_active ? 'check' : 'times' }}"></i>
                                    {{ $method->is_active ? 'Kích hoạt' : 'Tạm dừng' }}
                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.payment-methods.edit', $method) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" 
                                          class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
            
            {{ $paymentMethods->links() }}
        @else
            <div class="empty-state">
                <i class="fas fa-credit-card"></i>
                <p>Chưa có phương thức thanh toán nào</p>
                <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Phương Thức Đầu Tiên
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('extra_js')
<script>
function toggleStatus(id, button) {
    fetch(`/admin/payment-methods/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            const text = button.querySelector('i').nextSibling;
            
            if (data.is_active) {
                button.className = 'btn btn-sm btn-toggle btn-success';
                icon.className = 'fas fa-check';
                button.innerHTML = '<i class="fas fa-check"></i> Kích hoạt';
            } else {
                button.className = 'btn btn-sm btn-toggle btn-secondary';
                icon.className = 'fas fa-times';
                button.innerHTML = '<i class="fas fa-times"></i> Tạm dừng';
            }
            
            // Show toast notification
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${data.message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    });
}
</script>
@endsection