<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class ImageService
{
    protected $manager;
    protected const PROPERTY_DIR = 'properties';
    protected const BLOG_DIR = 'blog';
    protected const WRITER_DIR = 'blog-writers';

    public function __construct()
    {
        // Explicitly initialize with GD driver for v4.0.4
        $this->manager = new ImageManager(new Driver());
    }

    public function processPropertyImage($file)
    {
        $image = $this->manager->decode($file);

        return $this->processAndStorePropertyImage($image);
    }

    public function processPropertyImageFromPublicPath(string $publicPath): ?string
    {
        $relativePath = $this->publicPathToRelative($publicPath);
        if ($relativePath === null || ! Storage::disk('public')->exists($relativePath)) {
            return null;
        }

        $absolutePath = Storage::disk('public')->path($relativePath);
        $image = $this->manager->decode($absolutePath);

        return $this->processAndStorePropertyImage($image);
    }

    protected function processAndStorePropertyImage($image): string
    {
        $image->scale(width: 1200);
        $this->applyCenterWatermark($image);

        $filename = self::PROPERTY_DIR.'/'.uniqid('', true).'.webp';
        $encoded = $image->encode(new WebpEncoder(quality: 80));

        if (! Storage::disk('public')->exists(self::PROPERTY_DIR)) {
            Storage::disk('public')->makeDirectory(self::PROPERTY_DIR);
        }

        Storage::disk('public')->put($filename, (string) $encoded);

        return '/storage/'.$filename;
    }

    protected function applyCenterWatermark($image): void
    {
        $watermarkPath = SystemSetting::get('watermark_path');
        if (! $watermarkPath) {
            return;
        }

        if (! Storage::disk('public')->exists($watermarkPath)) {
            Log::warning('Watermark file missing on public disk.', ['watermark_path' => $watermarkPath]);

            return;
        }

        try {
            $watermarkFile = Storage::disk('public')->path($watermarkPath);
            $watermark = $this->manager->decode($watermarkFile);
            $targetWidth = max(140, (int) floor($image->width() * 0.35));
            $watermark->scale(width: $targetWidth);

            // Intervention v4 order: (image, x, y, alignment)
            $image->insert($watermark, 0, 0, 'center');
        } catch (\Throwable $e) {
            Log::warning('Failed to apply center watermark.', [
                'watermark_path' => $watermarkPath,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function publicPathToRelative(string $path): ?string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $trimmed)) {
            return null;
        }

        if (str_starts_with($trimmed, '/storage/')) {
            return ltrim(substr($trimmed, strlen('/storage/')), '/');
        }

        return ltrim($trimmed, '/');
    }

    public function processBlogFeaturedImage($file): string
    {
        return $this->processSimpleImage($file, self::BLOG_DIR, 1200, 82);
    }

    public function processBlogOgImage($file): string
    {
        return $this->processSimpleImage($file, self::BLOG_DIR, 1200, 82);
    }

    public function processBlogWriterPhoto($file): string
    {
        return $this->processSimpleImage($file, self::WRITER_DIR, 500, 85);
    }

    protected function processSimpleImage($file, string $directory, int $maxWidth, int $quality): string
    {
        $image = $this->manager->decode($file);
        $image->scale(width: $maxWidth);

        if (! Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = $directory.'/'.uniqid('', true).'.webp';
        $encoded = $image->encode(new WebpEncoder(quality: $quality));
        Storage::disk('public')->put($filename, (string) $encoded);

        return '/storage/'.$filename;
    }
}
