@extends('layouts.admin')

@section('page_title', 'Quản Lý Tài Khoản Admin')
@section('header_icon', 'fas fa-user-shield')

@section('content')
<div class="container-fluid">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h4 style="color: #8B3A3A; margin: 0;">
            <i class="fas fa-user-shield"></i> Quản Lý Tài Khoản Admin
        </h4>
        <a href="/admin/admins/create" class="btn btn-add">
            <i class="fas fa-plus"></i> Thêm Admin
        </a>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        @if($admins && count($admins) > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên Admin</th>
                        <th>Email</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td><span class="badge" style="background-color: #D4A574;">{{ $loop->iteration }}</span></td>
                        <td><strong>{{ $admin->name }}</strong></td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <span class="badge badge-status-active">
                                <i class="fas fa-check-circle"></i> Hoạt Động
                            </span>
                        </td>
                        <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="/admin/admins/{{ $admin->id }}/edit" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($admins->count() > 1)
                                    <form method="POST" action="/admin/admins/{{ $admin->id }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" onclick="return confirm('Bạn chắc chắn?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state" style="padding: 60px 20px; text-align: center; color: #999;">
                <i class="fas fa-user-shield" style="font-size: 60px; color: #D4A574; margin-bottom: 20px;"></i>
                <p style="font-size: 16px; margin-bottom: 20px;">Chưa có tài khoản admin nào</p>
            </div>
        @endif
    </div>

    @if($admins && $admins->hasPages())
        <div style="margin-top: 30px;">
            {{ $admins->links() }}
        </div>
    @endif
</div>

<style>
    .btn-add {
        background-color: #70AD47;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-add:hover {
        background-color: #56963D;
        color: white;
    }

    .table-responsive {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f5f1e8;
        color: #8B3A3A;
        border-bottom: 2px solid #D4A574;
        font-weight: 600;
        padding: 15px;
    }

    .table tbody tr {
        transition: background-color 0.3s;
    }

    .table tbody tr:hover {
        background-color: #f9f7f3;
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 12px;
    }

    .badge-status-active {
        background-color: #70AD47;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 10px;
        font-size: 13px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        color: white;
    }

    .btn-edit {
        background-color: #5B9BD5;
    }

    .btn-edit:hover {
        background-color: #3B7FB8;
        transform: translateY(-2px);
    }

    .btn-delete {
        background-color: #C5504B;
    }

    .btn-delete:hover {
        background-color: #A53D38;
        transform: translateY(-2px);
    }
</style>
@endsection
