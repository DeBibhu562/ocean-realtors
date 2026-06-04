<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Support\PropertyPayloadNormalizer;
use Illuminate\Console\Command;

class FixPropertyListingTitles extends Command
{
    protected $signature = 'properties:fix-listing-titles
        {--id=* : Only fix specific property IDs}
        {--all-legacy : Fix every listing with legacy machine titles (+ or -(id) suffix)}';

    protected $description = 'Rebuild human-readable property titles and SEO slugs for legacy machine-generated listings.';

    public function handle(): int
    {
        $ids = array_filter(array_map('intval', (array) $this->option('id')));
        $fixAllLegacy = (bool) $this->option('all-legacy');

        $query = Property::query()->orderBy('id');

        if ($ids !== []) {
            $query->whereIn('id', $ids);
        } elseif ($fixAllLegacy) {
            $query->where(function ($builder) {
                $builder->where('title', 'like', '%+%')
                    ->orWhere('title', 'like', '%-(%');
            });
        } else {
            $this->error('Pass --id=50 --id=51 or --all-legacy to select listings to update.');

            return self::FAILURE;
        }

        $updated = 0;
        $onlyLegacy = $fixAllLegacy && $ids === [];

        $query->each(function (Property $property) use (&$updated, $onlyLegacy) {
            if ($onlyLegacy && ! PropertyPayloadNormalizer::usesLegacyMachineTitle((string) $property->title)) {
                return;
            }

            $payload = PropertyPayloadNormalizer::payloadFromPropertyArray($property->toArray());
            $seo = PropertyPayloadNormalizer::applyComputedTitleAndSeo($payload);
            $slug = PropertyPayloadNormalizer::buildListingSlug($payload, $property->id);

            $property->update([
                'title' => $seo['title'],
                'slug' => $slug,
                'meta_title' => $seo['meta_title'],
                'meta_description' => $seo['meta_description'],
            ]);

            $this->line("Updated #{$property->id}: {$seo['title']} → /properties/{$slug}");
            $updated++;
        });

        $this->info("Done. {$updated} listing(s) updated.");

        return self::SUCCESS;
    }
}
