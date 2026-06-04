<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Normalizes validated listing payloads before Eloquent save.
 * Empty strings on nullable numeric columns cause MySQL 1366 errors.
 */
final class PropertyPayloadNormalizer
{
    /** @var list<string> */
    private const STRIP_KEYS = [
        'existing_photos',
        'new_photos',
        'save_as_draft',
        'title_locked',
        '_method',
    ];

    /** @var list<string> */
    private const NULLABLE_INTEGER = [
        'floor_no',
        'total_floors',
        'workstations',
        'cabins',
        'age_of_property',
        'balconies',
        'bathrooms',
        'bedrooms',
        'garage',
        'size',
        'completion_score',
        'views_count',
    ];

    /** @var list<string> */
    private const NULLABLE_DECIMAL = [
        'built_up_area',
        'carpet_area',
        'booking_amount',
        'food_charges',
        'maintenance_charges',
        'latitude',
        'longitude',
        'booking_amount',
    ];

    /** @var array<string, string> */
    private const NON_NULLABLE_STRING_DEFAULTS = [
        'parking_charges_type' => 'Separate',
        'furnish_type' => 'Unfurnished',
        'covered_parking' => '0',
        'open_parking' => '0',
    ];

    /** @var list<string> */
    private const NON_NEGATIVE_INTEGER = [
        'floor_no',
        'total_floors',
        'garage',
        'age_of_property',
        'balconies',
        'bathrooms',
        'bedrooms',
        'size',
    ];

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    public static function forStorage(array $validated): array
    {
        foreach (self::STRIP_KEYS as $key) {
            unset($validated[$key]);
        }

        foreach (self::NULLABLE_INTEGER as $key) {
            if (array_key_exists($key, $validated)) {
                $validated[$key] = self::normalizeInt($validated[$key]);
            }
        }

        foreach (self::NON_NEGATIVE_INTEGER as $key) {
            if (array_key_exists($key, $validated) && $validated[$key] !== null) {
                $validated[$key] = max(0, (int) $validated[$key]);
            }
        }

        foreach (self::NULLABLE_DECIMAL as $key) {
            if (array_key_exists($key, $validated)) {
                $validated[$key] = self::normalizeDecimal($validated[$key]);
            }
        }

        $validated = self::applyNonNullableStringDefaults($validated);

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private static function applyNonNullableStringDefaults(array $payload): array
    {
        foreach (self::NON_NULLABLE_STRING_DEFAULTS as $key => $default) {
            if (! array_key_exists($key, $payload) || self::isBlank($payload[$key])) {
                $payload[$key] = $default;
            }
        }

        $payload['maintenance_type'] = self::resolveMaintenanceType($payload);

        foreach (['covered_parking', 'open_parking'] as $parkingKey) {
            if (! array_key_exists($parkingKey, $payload)) {
                continue;
            }

            $digits = preg_replace('/\D+/', '', (string) $payload[$parkingKey]);
            $payload[$parkingKey] = $digits !== '' ? $digits : '0';
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function resolveMaintenanceType(array $payload): string
    {
        if (! self::isBlank($payload['maintenance_type'] ?? null)) {
            return trim((string) $payload['maintenance_type']);
        }

        return trim((string) ($payload['listing_type'] ?? '')) === 'Sell' ? 'N/A' : 'Separate';
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function applyComputedTitleAndSeo(array $payload, ?int $propertyId = null): array
    {
        $title = self::buildCanonicalTitle($payload);
        $payload['title'] = $title;

        if (! array_key_exists('meta_title', $payload) || self::isBlank($payload['meta_title'])) {
            $payload['meta_title'] = self::buildMetaTitle($title, $payload);
        }

        if (! array_key_exists('meta_description', $payload) || self::isBlank($payload['meta_description'])) {
            $payload['meta_description'] = self::buildMetaDescription($payload);
        }

        return $payload;
    }

    /**
     * Human-readable listing title, e.g. "3 BHK Builder Floor in DLF Phase 2, Gurgaon".
     *
     * @param  array<string, mixed>  $payload
     */
    public static function buildCanonicalTitle(array $payload): string
    {
        $bhk = self::formatBhkSegment((string) ($payload['bhk'] ?? ''));
        $type = self::formatTitleCaseSegment((string) ($payload['type'] ?? ''));
        $locality = self::resolveLocationSegment($payload);
        $city = self::formatTitleCaseSegment((string) ($payload['city'] ?? ''));

        $headline = trim(implode(' ', array_filter([$bhk, $type])));

        if ($headline === '' && $locality === '' && $city === '') {
            return 'Property Listing';
        }

        if ($locality !== '' && $city !== '') {
            $location = $locality.', '.$city;
        } else {
            $location = $locality !== '' ? $locality : $city;
        }

        if ($headline === '') {
            return Str::limit($location, 255, '');
        }

        if ($location === '') {
            return Str::limit($headline, 255, '');
        }

        return Str::limit($headline.' in '.$location, 255, '');
    }

    /**
     * SEO-friendly slug with property id suffix, e.g. "3-bhk-builder-floor-in-dlf-phase-2-gurgaon-50".
     *
     * @param  array<string, mixed>  $payload
     */
    public static function buildListingSlug(array $payload, int $propertyId): string
    {
        $base = Str::slug(self::buildCanonicalTitle($payload));

        if ($base === '') {
            return 'property-'.$propertyId;
        }

        return Str::limit($base.'-'.$propertyId, 255, '');
    }

    /**
     * @param  array<string, mixed>  $property
     * @return array<string, mixed>
     */
    public static function payloadFromPropertyArray(array $property): array
    {
        return [
            'bhk' => $property['bhk'] ?? '',
            'type' => $property['type'] ?? '',
            'locality' => $property['locality'] ?? '',
            'area' => $property['area'] ?? '',
            'society_name' => $property['society_name'] ?? '',
            'city' => $property['city'] ?? '',
            'listing_type' => $property['listing_type'] ?? '',
            'price' => $property['price'] ?? null,
        ];
    }

    public static function usesLegacyMachineTitle(string $title): bool
    {
        $title = trim($title);

        if ($title === '') {
            return false;
        }

        return str_contains($title, '+') || (bool) preg_match('/-\(\d+\)$/', $title);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function buildMetaTitle(string $title, array $payload): string
    {
        $listingType = trim((string) ($payload['listing_type'] ?? ''));

        $meta = trim($title);
        if ($listingType !== '') {
            $meta .= ' | For '.$listingType;
        }
        $meta .= ' | Ocean Realtors';

        return Str::limit($meta, 70, '');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function buildMetaDescription(array $payload): string
    {
        $parts = array_values(array_filter([
            (string) ($payload['bhk'] ?? ''),
            (string) ($payload['type'] ?? ''),
            (string) ($payload['listing_type'] ?? ''),
            self::resolveLocationSegment($payload),
            (string) ($payload['city'] ?? ''),
        ]));

        $summary = trim(implode(' ', $parts));
        $price = $payload['price'] ?? null;
        $priceText = is_numeric($price) ? ' at Rs '.number_format((float) $price) : '';

        return Str::limit(
            ($summary !== '' ? $summary : 'Property listing').' with verified details'.$priceText.'. Contact Ocean Realtors for visit and deal support.',
            160,
            ''
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function resolveLocationSegment(array $payload): string
    {
        foreach (['locality', 'area', 'society_name'] as $key) {
            $value = trim((string) ($payload[$key] ?? ''));
            if ($value !== '') {
                return self::formatTitleCaseSegment($value);
            }
        }

        return '';
    }

    private static function formatBhkSegment(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/^(\d+)\s*bhk\b/i', $value, $matches)) {
            return $matches[1].' BHK';
        }

        return preg_replace('/\bbhk\b/i', 'BHK', self::formatTitleCaseSegment($value));
    }

    private static function formatTitleCaseSegment(string $value): string
    {
        $value = preg_replace('/\s+/', ' ', trim($value));
        if ($value === '') {
            return '';
        }

        $words = explode(' ', $value);

        return collect($words)
            ->map(function (string $word): string {
                if ($word === '' || preg_match('/^\d+$/', $word)) {
                    return $word;
                }

                if (strlen($word) <= 4 && ctype_upper($word)) {
                    return $word;
                }

                return Str::ucfirst(Str::lower($word));
            })
            ->implode(' ');
    }

    private static function isBlank(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }

    private static function normalizeInt(mixed $value): ?int
    {
        if (self::isBlank($value)) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }

    private static function normalizeDecimal(mixed $value): ?float
    {
        if (self::isBlank($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return null;
    }
}
