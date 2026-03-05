@extends('layouts.admin')

@section('title', 'Quản Lý Liên Hệ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-envelope"></i> Quản Lý Liên Hệ</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($contacts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có liên hệ nào</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">Trạng thái</th>
                                <th>Người gửi</th>
                                <th>Chủ đề</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                            <tr class="{{ !$contact->is_read ? 'table-primary' : '' }}">
                                <td>
                                    @if(!$contact->is_read)
                                        <span class="badge bg-primary">Mới</span>
                                    @elseif($contact->admin_reply)
                                        <span class="badge bg-success">Đã trả lời</span>
                                    @else
                                        <span class="badge bg-secondary">Đã đọc</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $contact->name }}</strong><br>
                                    <small class="text-muted">{{ $contact->email }}</small>
                                    @if($contact->phone)
                                        <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $contact->phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $contact->subject }}</td>
                                <td>{{ Str::limit($contact->message, 50) }}</td>
                                <td>
                                    {{ $contact->created_at->format('H:i d/m/Y') }}<br>
                                    <small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-primary mb-1">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
