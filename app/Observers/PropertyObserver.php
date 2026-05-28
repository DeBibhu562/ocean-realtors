<?php

namespace App\Observers;

use App\Models\Property;
use App\Support\PropertyListingCache;

class PropertyObserver
{
    public function saved(Property $property): void
    {
        try {
            PropertyListingCache::bump();
        } catch (\Throwable) {
            //
        }
    }

    public function deleted(Property $property): void
    {
        try {
            PropertyListingCache::bump();
        } catch (\Throwable) {
            //
        }
    }
}
