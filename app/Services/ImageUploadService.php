<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class ImageUploadService
{
    private function getCloudinary(): ?Cloudinary
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey    = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            return null;
        }

        $config = new Configuration();
        $config->cloud->cloudName  = $cloudName;
        $config->cloud->apiKey     = $apiKey;
        $config->cloud->apiSecret  = $apiSecret;
        $config->url->secure       = true;

        return new Cloudinary($config);
    }

    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $cloudinary = $this->getCloudinary();

        if ($cloudinary) {
            $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder'        => 'fashion-shop/' . $folder,
                'resource_type' => 'image',
            ]);
            return $result['secure_url'];
        }

        return $file->store($folder, 'public');
    }

    public function delete(?string $path): void
    {
        if (!$path) return;

        if ($this->isCloudinaryUrl($path)) {
            $cloudinary = $this->getCloudinary();
            if ($cloudinary) {
                $publicId = $this->extractPublicId($path);
                if ($publicId) {
                    $cloudinary->uploadApi()->destroy($publicId);
                }
            }
        } else {
            Storage::disk('public')->delete($path);
        }
    }

    public static function url(?string $path): ?string
    {
        if (!$path) return null;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    private function isCloudinaryUrl(string $path): bool
    {
        return str_contains($path, 'cloudinary.com');
    }

    private function extractPublicId(string $url): ?string
    {
        if (preg_match('/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
