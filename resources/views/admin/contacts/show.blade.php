@extends('layouts.admin')

@section('title', 'Chi Tiết Liên Hệ')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Contact Details -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Thông Tin Liên Hệ</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-user"></i> Người gửi:</strong><br>
                            {{ $contact->name }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-envelope"></i> Email:</strong><br>
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </div>
                    </div>

                    @if($contact->phone)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-phone"></i> Số điện thoại:</strong><br>
                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-clock"></i> Thời gian:</strong><br>
                            {{ $contact->created_at->format('H:i d/m/Y') }}
                        </div>
                    </div>
                    @else
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong><i class="fas fa-clock"></i> Thời gian:</strong><br>
                            {{ $contact->created_at->format('H:i d/m/Y') }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong><i class="fas fa-tag"></i> Chủ đề:</strong><br>
                        {{ $contact->subject }}
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-comment"></i> Nội dung:</strong>
                        <div class="p-3 bg-light rounded mt-2">
                            {{ $contact->message }}
                        </div>
                    </div>

                    @if($contact->admin_reply)
                    <div class="alert alert-success">
                        <strong><i class="fas fa-reply"></i> Đã trả lời:</strong>
                        <div class="mt-2">{{ $contact->admin_reply }}</div>
                        <small class="text-muted">
                            Bởi {{ $contact->repliedBy->name ?? 'Admin' }} 
                            vào {{ $contact->replied_at->format('H:i d/m/Y') }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reply Form -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-reply"></i> Trả Lời</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nội dung trả lời</label>
                            <textarea name="admin_reply" class="form-control @error('admin_reply') is-invalid @enderror" rows="5" required>{{ old('admin_reply', $contact->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Nội dung này sẽ được gửi qua email đến khách hàng (tính năng sẽ được bổ sung)
                            </small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> {{ $contact->admin_reply ? 'Cập nhật trả lời' : 'Gửi trả lời' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
