@extends('layouts.admin')

@section('title', 'Quản Lý Trang Về Chúng Tôi')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="fas fa-info-circle"></i> Quản Lý Trang Về Chúng Tôi</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Nội Dung Trang</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề trang *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $about->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giới thiệu / Câu chuyện thương hiệu</label>
                    <textarea name="intro" class="form-control @error('intro') is-invalid @enderror" rows="4">{{ old('intro', $about->intro) }}</textarea>
                    @error('intro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tầm nhìn</label>
                    <textarea name="vision" class="form-control @error('vision') is-invalid @enderror" rows="3">{{ old('vision', $about->vision) }}</textarea>
                    @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sứ mệnh</label>
                    <textarea name="mission" class="form-control @error('mission') is-invalid @enderror" rows="3">{{ old('mission', $about->mission) }}</textarea>
                    @error('mission')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giá trị cốt lõi</label>
                    <textarea name="core_values" class="form-control @error('core_values') is-invalid @enderror" rows="5" placeholder="Mỗi giá trị 1 dòng, ví dụ:&#10;Chất lượng: Cam kết chất lượng sản phẩm tốt nhất&#10;Dịch vụ: Phục vụ khách hàng tận tâm">{{ old('core_values', $about->core_values) }}</textarea>
                    @error('core_values')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Mỗi giá trị một dòng</small>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Hình Ảnh</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Ảnh {{ $i }}</label>
                        
                        @php $fieldName = "image_$i"; @endphp
                        @if($about->$fieldName)
                            <div class="mb-2 position-relative">
                                <img src="{{ asset('storage/' . $about->$fieldName) }}" class="img-thumbnail" style="max-height: 200px; width: 100%; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="deleteImage({{ $i }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endif
                        
                        <input type="file" name="image_{{ $i }}" class="form-control @error('image_'.$i) is-invalid @enderror" accept="image/*">
                        @error('image_'.$i)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Kích thước tối đa: 5MB</small>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                <i class="fas fa-save"></i> Lưu Thay Đổi
            </button>
            <a href="{{ route('about') }}" target="_blank" class="btn btn-secondary btn-lg">
                <i class="fas fa-eye"></i> Xem Trang
            </a>
        </div>
    </form>
</div>

<script>
// Handle form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
});

// Handle image deletion
function deleteImage(imageNumber) {
    if (!confirm('Xóa ảnh này?')) {
        return;
    }
    
    // Create a hidden form to submit delete request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("admin/about/image") }}/' + imageNumber;
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add DELETE method
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
