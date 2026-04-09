@extends('layouts.admin')

@section('title', 'Quản Lý Tài Khoản')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> Quản Lý Tài Khoản</h1>
    <a href="/admin/admins/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Admin
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Search -->
<form method="GET" action="{{ request()->url() }}" class="mb-4">
    <div class="input-group" style="max-width: 420px;">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Tìm theo tên, email, số điện thoại...">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        @if(request('search'))
            <a href="{{ request()->url() }}" class="btn btn-outline-secondary">Xóa</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px;">STT</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th style="width:80px;">Ảnh</th>
                        <th>Ngày tham gia</th>
                        <th>Phân quyền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td><strong>{{ $u->name }}</strong>
                            @if($u->id === auth()->id())
                                <span class="badge bg-warning text-dark ms-1">Bạn</span>
                            @endif
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if($u->avatar)
                                <img src="/storage/{{ $u->avatar }}" alt="{{ $u->name }}"
                                     class="rounded-circle" style="width:44px;height:44px;object-fit:cover;border:2px solid #dee2e6;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width:44px;height:44px;background:{{ $u->is_admin ? '#8B3A3A' : '#6c757d' }};">
                                    <i class="fas fa-{{ $u->is_admin ? 'user-shield' : 'user' }} text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $u->created_at->diffForHumans() }}</small></td>
                        <td>
                            @if($u->is_admin)
                                <span class="badge" style="background:#8B3A3A;">admin</span>
                            @else
                                <span class="badge bg-secondary">user</span>
                            @endif
                        </td>
                        <td><span class="badge bg-success">active</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($u->is_admin)
                                    <a href="/admin/admins/{{ $u->id }}/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="/admin/admins/{{ $u->id }}"
                                              class="d-inline" onsubmit="return confirm('Xác nhận xóa admin này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Xác nhận xóa người dùng này?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i> Chưa có tài khoản nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="d-flex justify-content-center p-3">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
