@extends('layouts.app')

@section('title', ($about->title ?? 'Về Chúng Tôi') . ' - Fashion Shop')

@section('content')
<style>
    .about-page * {
        font-family: 'Times New Roman', Times, serif !important;
    }
    
    .about-page h1 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #8B3A3A;
    }
    
    .about-page h3 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #8B3A3A;
    }
    
    .about-page h4 {
        font-size: 1.4rem;
        font-weight: bold;
        color: #2B2B2B;
    }
    
    .about-page p,
    .about-page li {
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: justify;
    }
    
    .about-page .lead {
        font-size: 1.3rem;
        font-weight: 500;
    }
    
    .about-image {
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .about-image-main {
        height: 450px;
        margin-bottom: 1.5rem;
    }
    
    .about-image-secondary {
        height: 350px;
    }
</style>

<div class="container py-5 about-page">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="text-center mb-5">{{ $about->title ?? 'Về Fashion Shop' }}</h1>
            
            @if($about)
                <!-- Images Section -->
                @if($about->image_1 || $about->image_2 || $about->image_3)
                <div class="mb-5">
                    @if($about->image_1)
                    <div class="mb-3">
                        <img src="{{ \App\Services\ImageUploadService::url($about->image_1) }}" alt="About Image 1" class="about-image about-image-main">
                    </div>
                    @endif
                    
                    @if($about->image_2 || $about->image_3)
                    <div class="row">
                        @if($about->image_2)
                        <div class="col-md-6 mb-3">
                            <img src="{{ \App\Services\ImageUploadService::url($about->image_2) }}" alt="About Image 2" class="about-image about-image-secondary">
                        </div>
                        @endif
                        
                        @if($about->image_3)
                        <div class="col-md-6 mb-3">
                            <img src="{{ \App\Services\ImageUploadService::url($about->image_3) }}" alt="About Image 3" class="about-image about-image-secondary">
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif
                
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        @if($about->intro)
                        <h3 class="mb-4">Câu chuyện thương hiệu</h3>
                        <p class="lead">{{ $about->intro }}</p>
                        @endif
                        
                        @if($about->vision)
                        <h4 class="mt-4 mb-3">Tầm nhìn</h4>
                        <p>{{ $about->vision }}</p>
                        @endif
                        
                        @if($about->mission)
                        <h4 class="mt-4 mb-3">Sứ mệnh</h4>
                        <p>{{ $about->mission }}</p>
                        @endif
                        
                        @if($about->core_values)
                        <h4 class="mt-4 mb-3">Giá trị cốt lõi</h4>
                        <ul>
                            @foreach(explode("\n", $about->core_values) as $value)
                                @if(trim($value))
                                <li>{!! nl2br(e(trim($value))) !!}</li>
                                @endif
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            @else
                <!-- Default content if no data -->
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="mb-4">Câu chuyện thương hiệu</h3>
                        <p class="lead">Fashion Shop được thành lập với sứ mệnh mang đến những sản phẩm thời trang cao cấp, phù hợp với phong cách hiện đại của người Việt Nam.</p>
                        
                        <h4 class="mt-4 mb-3">Tầm nhìn</h4>
                        <p>Trở thành thương hiệu thời trang hàng đầu Việt Nam, được khách hàng tin tưởng và yêu thích.</p>
                        
                        <h4 class="mt-4 mb-3">Sứ mệnh</h4>
                        <p>Cung cấp những sản phẩm thời trang chất lượng cao với giá cả hợp lý, giúp khách hàng tự tin thể hiện phong cách cá nhân.</p>
                        
                        <h4 class="mt-4 mb-3">Giá trị cốt lõi</h4>
                        <ul>
                            <li><strong>Chất lượng:</strong> Cam kết chất lượng sản phẩm tốt nhất</li>
                            <li><strong>Dịch vụ:</strong> Phục vụ khách hàng tận tâm, chu đáo</li>
                            <li><strong>Uy tín:</strong> Xây dựng niềm tin với khách hàng</li>
                            <li><strong>Đổi mới:</strong> Luôn cập nhật xu hướng thời trang mới</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection