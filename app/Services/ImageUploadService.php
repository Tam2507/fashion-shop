<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageUploadService
{
    /**
     * Upload ảnh — tự động dùng Cloudinary nếu có config, fallback về local.
     * Trả về path/URL để lưu vào DB.
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        if ($this->cloudinaryConfigured()) {
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'fashion-shop/' . $folder,
                'resource_type' => 'image',
            ]);
            return $result->getSecurePath();
        }

        return $file->store($folder, 'public');
    }

    /**
     * Xóa ảnh — xóa trên Cloudinary nếu là URL, xóa local nếu là path.
     */
    public function delete(?string $path): void
    {
        if (!$path) return;

        if ($this->isCloudinaryUrl($path)) {
            $publicId = $this->extractPublicId($path);
            if ($publicId) {
                Cloudinary::destroy($publicId);
            }
        } else {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Trả về URL hiển thị ảnh.
     * Nếu là Cloudinary URL thì trả thẳng, nếu là local path thì dùng asset().
     */
    public static function url(?string $path): ?string
    {
        if (!$path) return null;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    private function cloudinaryConfigured(): bool
    {
        return !empty(config('cloudinary.cloud_url'))
            || !empty(env('CLOUDINARY_CLOUD_NAME'));
    }

    private function isCloudinaryUrl(string $path): bool
    {
        return str_contains($path, 'cloudinary.com');
    }

    private function extractPublicId(string $url): ?string
    {
        // URL dạng: https://res.cloudinary.com/{cloud}/image/upload/v123/fashion-shop/folder/filename.ext
        if (preg_match('/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
