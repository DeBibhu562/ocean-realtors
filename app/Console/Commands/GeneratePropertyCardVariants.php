<?php

namespace App\Console\Commands;

use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GeneratePropertyCardVariants extends Command
{
    protected $signature = 'properties:generate-card-variants
        {--chunk=50 : Files processed before progress output}
        {--limit=0 : Max base images to process (0 = all)}';

    protected $description = 'Generate missing 480/640/960 webp variants for property card srcset.';

    public function handle(ImageService $imageService): int
    {
        $chunk = max((int) $this->option('chunk'), 1);
        $limit = max((int) $this->option('limit'), 0);

        $files = collect(Storage::disk('public')->files('properties'))
            ->filter(fn (string $path) => preg_match('/\.webp$/i', $path) && ! preg_match('/-\d+\.webp$/i', $path))
            ->values();

        if ($limit > 0) {
            $files = $files->take($limit);
        }

        $total = $files->count();
        if ($total === 0) {
            $this->info('No base property images found.');

            return self::SUCCESS;
        }

        $this->info("Processing {$total} base property images…");

        $processed = 0;
        $created = 0;
        $failed = 0;

        foreach ($files as $relativePath) {
            $processed++;

            try {
                $created += $imageService->ensureCardVariantsForPublicPath('/storage/'.$relativePath);
            } catch (\Throwable $e) {
                $failed++;
                $this->warn("Failed {$relativePath}: {$e->getMessage()}");
            }

            if ($processed % $chunk === 0 || $processed === $total) {
                $this->line("Progress: {$processed}/{$total} (variants created: {$created}, failed: {$failed})");
            }
        }

        $this->newLine();
        $this->info("Done. Base images: {$total}, variants created: {$created}, failed: {$failed}");

        return self::SUCCESS;
    }
}
