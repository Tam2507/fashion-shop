@extends('layouts.admin')

@section('title', 'Cấu hình SePay IPN')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Cấu hình SePay</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- IPN URL --}}
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-link me-2"></i>URL nhận thông báo IPN</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">
                        Sao chép URL bên dưới và dán vào phần <strong>Cấu hình IPN</strong> trên dashboard SePay của bạn.
                        SePay sẽ gửi POST request tới URL này khi có giao dịch thanh toán thành công.
                    </p>
                    <div class="input-group">
                        <input type="text" class="form-control font-monospace" id="ipnUrl"
                               value="{{ $ipnUrl }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyIpnUrl()">
                            <i class="fas fa-copy me-1"></i>Sao chép
                        </button>
                    </div>
                    <div class="mt-2">
                        <small class="text-success d-none" id="copySuccess">
                            <i class="fas fa-check me-1"></i>Đã sao chép!
                        </small>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Hướng dẫn:</strong> Đăng nhập vào
                        <a href="https://my.sepay.vn" target="_blank">my.sepay.vn</a>
                        → Tài khoản ngân hàng → Chọn tài khoản → Cấu hình IPN → Dán URL trên vào ô
                        <em>"URL nhận thông báo"</em> → Lưu lại.
                    </div>
                </div>
            </div>

            {{-- Credentials --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Thông tin xác thực</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.sepay.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Merchant ID (SEPAY_MERCHANT_ID)</label>
                            <input type="text" name="merchant_id" class="form-control font-monospace"
                                   value="{{ config('services.sepay.merchant_id') }}"
                                   placeholder="Nhập Merchant ID từ SePay">
                            <div class="form-text">Tìm trong: SePay Dashboard → Cài đặt → Thông tin tài khoản</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">API Key (SEPAY_API_KEY)</label>
                            <input type="text" name="api_key" class="form-control font-monospace"
                                   value="{{ config('services.sepay.api_key') }}"
                                   placeholder="Nhập API Key từ SePay">
                            <div class="form-text">
                                Tìm trong: SePay Dashboard → Cài đặt → API Key.
                                Dùng để xác thực request IPN từ SePay (header: <code>Authorization: Apikey YOUR_KEY</code>).
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Môi trường</label>
                            <select name="env" class="form-select">
                                <option value="production" {{ config('services.sepay.env') === 'production' ? 'selected' : '' }}>
                                    Production (Thật)
                                </option>
                                <option value="sandbox" {{ config('services.sepay.env') === 'sandbox' ? 'selected' : '' }}>
                                    Sandbox (Thử nghiệm)
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Lưu cấu hình
                        </button>
                    </form>
                </div>
            </div>

            {{-- Trạng thái hiện tại --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Trạng thái cấu hình</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-semibold" style="width:200px">SEPAY_MERCHANT_ID</td>
                                <td>
                                    @if(config('services.sepay.merchant_id'))
                                        <span class="badge bg-success">Đã cấu hình</span>
                                        <code class="ms-2">{{ Str::mask(config('services.sepay.merchant_id'), '*', 4) }}</code>
                                    @else
                                        <span class="badge bg-danger">Chưa cấu hình</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">SEPAY_API_KEY</td>
                                <td>
                                    @if(config('services.sepay.api_key'))
                                        <span class="badge bg-success">Đã cấu hình</span>
                                        <code class="ms-2">{{ Str::mask(config('services.sepay.api_key'), '*', 4) }}</code>
                                    @else
                                        <span class="badge bg-warning text-dark">Chưa cấu hình (IPN không xác thực)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">SEPAY_ENV</td>
                                <td>
                                    <span class="badge {{ config('services.sepay.env') === 'production' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ config('services.sepay.env', 'sandbox') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function copyIpnUrl() {
    const input = document.getElementById('ipnUrl');
    navigator.clipboard.writeText(input.value).then(() => {
        document.getElementById('copySuccess').classList.remove('d-none');
        setTimeout(() => document.getElementById('copySuccess').classList.add('d-none'), 2000);
    });
}
</script>
@endpush
@endsection
