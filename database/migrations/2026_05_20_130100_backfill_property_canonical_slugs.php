<?php

use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Property::query()->orderBy('id')->chunkById(200, function ($properties): void {
            foreach ($properties as $property) {
                $oldSlug = (string) $property->slug;
                $canonical = Property::canonicalSlug((string) $property->title, (int) $property->id);

                if ($oldSlug === $canonical) {
                    continue;
                }

                if ($oldSlug !== '') {
                    DB::table('property_slug_histories')->insertOrIgnore([
                        'property_id' => $property->id,
                        'old_slug' => $oldSlug,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $property->slug = $canonical;
                $property->saveQuietly();
            }
        });
    }

    public function down(): void
    {
        // Backfill is intentionally irreversible.
    }
};
