@extends('layouts.app')

@section('title', 'Thông Tin Cá Nhân')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="mb-4"><i class="fas fa-user-circle"></i> Thông Tin Cá Nhân</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Avatar Section -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3"><i class="fas fa-camera"></i> Ảnh Đại Diện</h5>
                    
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ \App\Services\ImageUploadService::url($user->avatar) }}" 
                                 alt="Avatar" 
                                 id="avatarPreview"
                                 class="rounded-circle" 
                                 style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;">
                        @else
                            <div id="avatarPreview" 
                                 class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; border: 3px solid #ddd;">
                                <i class="fas fa-user fa-4x text-white"></i>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="phone" value="{{ $user->phone }}">
                        <input type="hidden" name="address" value="{{ $user->address }}">
                        <input type="file" 
                               name="avatar" 
                               id="avatar" 
                               class="d-none" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               onchange="previewAndSubmit(this)">
                        <label for="avatar" class="btn btn-primary">
                            <i class="fas fa-camera"></i> Thay đổi ảnh
                        </label>
                    </form>

                    @if($user->avatar)
                        <form action="{{ route('profile.delete-avatar') }}" method="POST" class="d-inline mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa ảnh đại diện?')">
                                <i class="fas fa-trash"></i> Xóa ảnh
                            </button>
                        </form>
                    @endif
                </div>
            </div>

<script>
function previewAndSubmit(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        // Check file size
        if (file.size > maxSize) {
            alert('Ảnh không được vượt quá 5MB!');
            input.value = '';
            return;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            
            // Replace with img tag if it's a div
            if (preview.tagName === 'DIV') {
                const img = document.createElement('img');
                img.id = 'avatarPreview';
                img.className = 'rounded-circle';
                img.style.cssText = 'width: 150px; height: 150px; object-fit: cover; border: 3px solid #ddd;';
                img.src = e.target.result;
                preview.parentNode.replaceChild(img, preview);
            } else {
                preview.src = e.target.result;
            }
            
            // Auto submit form after preview
            setTimeout(() => {
                input.form.submit();
            }, 500);
        }
        
        reader.readAsDataURL(file);
    }
}
</script>

            <!-- Profile Info -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông Tin Cơ Bản</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-key"></i> Đổi Mật Khẩu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields to preserve user data -->
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="phone" value="{{ $user->phone }}">
                        <input type="hidden" name="address" value="{{ $user->address }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Tối thiểu 8 ký tự</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-lock"></i> Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
