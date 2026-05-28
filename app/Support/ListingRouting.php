<?php

namespace App\Support;

/**
 * Mirrors resources/js/config/listingRouting.js for server-side validation.
 */
final class ListingRouting
{
    /**
     * @var array<string, array<string, list<string>>>
     */
    private const PROPERTY_TYPES = [
        'Residential' => [
            'Rent' => ['Apartment', 'Independent House', 'Villa', 'Builder Floor', 'Studio', 'Penthouse', 'Plot'],
            'Sell' => ['Apartment', 'Independent House', 'Villa', 'Builder Floor', 'Studio', 'Penthouse', 'Plot'],
            'PG' => ['Co-living Space', 'Student Hostel', 'Working Professionals PG', 'Room in Apartment'],
        ],
        'Commercial' => [
            'Rent' => ['Office Space', 'Retail Shop', 'Warehouse', 'Showroom', 'Commercial Land', 'Co-working'],
            'Sell' => ['Office Space', 'Retail Shop', 'Warehouse', 'Showroom', 'Commercial Land', 'Industrial Shed'],
            'PG' => [],
        ],
    ];

    /**
     * @return list<string>
     */
    public static function propertyTypes(string $category, string $listingType): array
    {
        return self::PROPERTY_TYPES[$category][$listingType] ?? [];
    }

    public static function isCombinationAllowed(string $category, string $listingType): bool
    {
        if ($listingType === 'PG' && $category === 'Commercial') {
            return false;
        }

        return isset(self::PROPERTY_TYPES[$category][$listingType]);
    }

    public static function defaultStatusLabel(string $listingType): string
    {
        return match ($listingType) {
            'Sell' => 'For Sale',
            'PG' => 'PG',
            default => 'For Rent',
        };
    }
}
