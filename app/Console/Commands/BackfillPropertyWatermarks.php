<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Services\ImageService;
use Illuminate\Console\Command;

class BackfillPropertyWatermarks extends Command
{
    protected $signature = 'properties:backfill-watermarks
        {--commit : Persist updated image paths (default mode is dry-run)}
        {--include-normalized : Also reprocess existing /storage/properties/*.webp images}
        {--chunk=100 : Number of properties per chunk}';

    protected $description = 'Backfill center-burned watermarks for local property images missing normalized processed paths.';

    public function handle(ImageService $imageService): int
    {
        $commit = (bool) $this->option('commit');
        $includeNormalized = (bool) $this->option('include-normalized');
        $chunk = max((int) $this->option('chunk'), 1);

        $this->info($commit
            ? 'Commit mode: updating property image paths.'
            : 'Dry-run mode: no files or DB rows will be changed.');

        $processedPathCache = [];
        $propertyCount = 0;
        $candidateCount = 0;
        $updatedCount = 0;
        $failedCount = 0;

        Property::query()
            ->select(['id', 'image', 'photos'])
            ->orderBy('id')
            ->chunkById($chunk, function ($properties) use (
                $imageService,
                $commit,
                $includeNormalized,
                &$processedPathCache,
                &$propertyCount,
                &$candidateCount,
                &$updatedCount,
                &$failedCount
            ) {
                foreach ($properties as $property) {
                    $propertyCount++;
                    $dirty = false;
                    $newImage = $property->image;
                    $newPhotos = is_array($property->photos) ? $property->photos : [];

                    if ($this->shouldBackfillPath($property->image, $includeNormalized)) {
                        $candidateCount++;
                        $result = $this->transformPath($property->image, $imageService, $commit, $processedPathCache);

                        if ($result['ok']) {
                            if ($result['new_path'] !== null && $result['new_path'] !== $property->image) {
                                $newImage = $result['new_path'];
                                $dirty = true;
                            }
                        } else {
                            $failedCount++;
                            $this->warn("Property {$property->id}: failed to process cover image {$property->image}");
                        }
                    }

                    foreach ($newPhotos as $index => $photoPath) {
                        if (! $this->shouldBackfillPath($photoPath, $includeNormalized)) {
                            continue;
                        }

                        $candidateCount++;
                        $result = $this->transformPath($photoPath, $imageService, $commit, $processedPathCache);

                        if ($result['ok']) {
                            if ($result['new_path'] !== null && $result['new_path'] !== $photoPath) {
                                $newPhotos[$index] = $result['new_path'];
                                $dirty = true;
                            }
                        } else {
                            $failedCount++;
                            $this->warn("Property {$property->id}: failed to process photo {$photoPath}");
                        }
                    }

                    if ($commit && $dirty) {
                        $property->update([
                            'image' => $newImage,
                            'photos' => array_values($newPhotos),
                        ]);
                        $updatedCount++;
                    }
                }
            });

        $this->newLine();
        $this->line("Properties scanned: {$propertyCount}");
        $this->line("Candidate images: {$candidateCount}");
        $this->line("Properties updated: ".($commit ? $updatedCount : 0));
        $this->line("Failed images: {$failedCount}");

        return self::SUCCESS;
    }

    protected function shouldBackfillPath(?string $path, bool $includeNormalized = false): bool
    {
        if (! is_string($path) || trim($path) === '') {
            return false;
        }

        $trimmed = trim($path);
        if (preg_match('/^https?:\/\//i', $trimmed)) {
            return false;
        }

        if ($includeNormalized) {
            return true;
        }

        return ! $this->isNormalizedPropertyPath($trimmed);
    }

    protected function isNormalizedPropertyPath(string $path): bool
    {
        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        }

        return (bool) preg_match('/^properties\/.+\.webp$/i', $normalized);
    }

    /**
     * @param  array<string, string|null>  $processedPathCache
     * @return array{ok: bool, new_path: string|null}
     */
    protected function transformPath(
        string $path,
        ImageService $imageService,
        bool $commit,
        array &$processedPathCache
    ): array {
        if (array_key_exists($path, $processedPathCache)) {
            return ['ok' => true, 'new_path' => $processedPathCache[$path]];
        }

        if (! $commit) {
            $processedPathCache[$path] = null;

            return ['ok' => true, 'new_path' => null];
        }

        try {
            $newPath = $imageService->processPropertyImageFromPublicPath($path);
            if (! $newPath) {
                return ['ok' => false, 'new_path' => null];
            }

            $processedPathCache[$path] = $newPath;

            return ['ok' => true, 'new_path' => $newPath];
        } catch (\Throwable) {
            return ['ok' => false, 'new_path' => null];
        }
    }
}
