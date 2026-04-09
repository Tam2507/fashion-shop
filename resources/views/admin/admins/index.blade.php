@extends('layouts.admin')

@section('title', 'Quản Lý Tài Khoản Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-shield"></i> Quản Lý Tài Khoản Admin</h1>
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
               class="form-control" placeholder="Tìm theo tên, email...">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        @if(request('search'))
            <a href="{{ request()->url() }}" class="btn btn-outline-secondary">Xóa</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Avatar</th>
                        <th>Tên Admin</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>
                            @if($admin->avatar)
                                <img src="/storage/{{ $admin->avatar }}" alt="{{ $admin->name }}"
                                     class="rounded-circle" style="width:48px;height:48px;object-fit:cover;border:2px solid #dee2e6;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:48px;height:48px;background:#8B3A3A;">
                                    <i class="fas fa-user-shield text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $admin->name }}</strong>
                            @if($admin->id === auth()->id())
                                <span class="badge bg-warning text-dark ms-1">Bạn</span>
                            @endif
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td><span class="badge bg-success"><i class="fas fa-check-circle"></i> Hoạt động</span></td>
                        <td><small class="text-muted">{{ $admin->created_at->format('d/m/Y') }}</small></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/admin/admins/{{ $admin->id }}/edit" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($admins->count() > 1 && $admin->id !== auth()->id())
                                    <form method="POST" action="/admin/admins/{{ $admin->id }}"
                                          class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa admin này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-user-shield text-muted" style="font-size:3rem;"></i>
                            <p class="text-muted mt-2">Chưa có tài khoản admin nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
