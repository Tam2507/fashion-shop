<?php

use App\Services\ImageUploadService;

if (!function_exists('img_url')) {
    /**
     * Trả về URL ảnh — tự động xử lý cả Cloudinary URL lẫn local storage path.
     */
    function img_url(?string $path): ?string
    {
        return ImageUploadService::url($path);
    }
}
