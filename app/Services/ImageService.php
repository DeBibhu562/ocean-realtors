<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Explicitly initialize with GD driver for v4.0.4
        $this->manager = new ImageManager(new Driver());
    }

    public function processPropertyImage($file)
    {
        // 1. Decode image using the v4 API
        $image = $this->manager->decode($file);

        // 2. Professional Resizing (Max 1200px width)
        $image->scale(width: 1200);

        // 3. Apply Watermark branding if configured
        $watermarkPath = SystemSetting::get('watermark_path');
        if ($watermarkPath && Storage::disk('public')->exists($watermarkPath)) {
            $watermarkFile = Storage::disk('public')->path($watermarkPath);
            
            // Decode the watermark file
            $watermark = $this->manager->decode($watermarkFile);
            
            // Branding scale: 20% of image width
            $watermark->scale(width: 240);
            
            // FINAL FIX: v4.0.4 Argument order is (image, x, y, alignment)
            $image->insert($watermark, 20, 20, 'bottom-right');
        }

        // 4. Optimize and save as WebP (80% Quality)
        $filename = 'properties/' . uniqid() . '.webp';
        
        // v4.0.4 professional encoding
        $encoded = $image->encode(new WebpEncoder(quality: 80));
        
        if (!Storage::disk('public')->exists('properties')) {
            Storage::disk('public')->makeDirectory('properties');
        }
        
        Storage::disk('public')->put($filename, (string) $encoded);

        return '/storage/' . $filename;
    }
}
