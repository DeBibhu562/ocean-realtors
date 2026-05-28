<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Lightweight cache-bust helper for public property listing API responses.
 */
final class PropertyListingCache
{
    public static function bump(): void
    {
        try {
            Cache::increment('api.properties.version');
        } catch (\Throwable) {
            //
        }
    }
}
