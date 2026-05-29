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

    /** @var list<int> */
    public const CARD_VARIANT_WIDTHS = [480, 640, 960];

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
        $uid = uniqid('', true);
        $baseFilename = self::PROPERTY_DIR.'/'.$uid.'.webp';

        if (! Storage::disk('public')->exists(self::PROPERTY_DIR)) {
            Storage::disk('public')->makeDirectory(self::PROPERTY_DIR);
        }

        $encodedOriginal = (string) $image->encode(new WebpEncoder(quality: 90));

        $this->writeScaledVariants($encodedOriginal, $baseFilename, [1200, ...self::CARD_VARIANT_WIDTHS]);

        return '/storage/'.$baseFilename;
    }

    /**
     * Create missing 480/640/960 card variants for an existing property image.
     */
    public function ensureCardVariantsForPublicPath(string $publicPath): int
    {
        $relativePath = $this->publicPathToRelative($publicPath);
        if ($relativePath === null || ! Storage::disk('public')->exists($relativePath)) {
            return 0;
        }

        if (preg_match('/-\d+\.webp$/i', $relativePath)) {
            return 0;
        }

        $absolutePath = Storage::disk('public')->path($relativePath);
        $encodedOriginal = (string) $this->manager->decode($absolutePath)->encode(new WebpEncoder(quality: 90));

        return $this->writeScaledVariants(
            $encodedOriginal,
            $relativePath,
            self::CARD_VARIANT_WIDTHS,
            skipExisting: true,
        );
    }

    /**
     * @param  list<int>  $widths
     */
    protected function writeScaledVariants(
        string $encodedOriginal,
        string $relativeBasePath,
        array $widths,
        bool $skipExisting = false,
    ): int {
        $created = 0;

        foreach ($widths as $width) {
            $filename = $width === 1200 && ! str_contains($relativeBasePath, '-')
                ? $relativeBasePath
                : preg_replace('/\.webp$/', '-'.$width.'.webp', $relativeBasePath);

            if ($skipExisting && Storage::disk('public')->exists($filename)) {
                continue;
            }

            $variant = $this->manager->decode($encodedOriginal);
            if ($variant->width() > $width) {
                $variant->scale(width: $width);
            }
            $this->applyCenterWatermark($variant);

            Storage::disk('public')->put(
                $filename,
                (string) $variant->encode(new WebpEncoder(quality: 80))
            );
            $created++;
        }

        return $created;
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
