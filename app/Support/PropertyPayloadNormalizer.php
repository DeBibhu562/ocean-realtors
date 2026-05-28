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

        foreach (self::NULLABLE_DECIMAL as $key) {
            if (array_key_exists($key, $validated)) {
                $validated[$key] = self::normalizeDecimal($validated[$key]);
            }
        }

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function applyComputedTitleAndSeo(array $payload, ?int $propertyId = null): array
    {
        $title = self::buildCanonicalTitle($payload, $propertyId);
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
     * @param  array<string, mixed>  $payload
     */
    private static function buildCanonicalTitle(array $payload, ?int $propertyId = null): string
    {
        $segments = array_values(array_filter([
            self::normalizedToken((string) ($payload['bhk'] ?? '')),
            self::normalizedToken((string) ($payload['type'] ?? '')),
            self::normalizedToken((string) ($payload['locality'] ?? $payload['area'] ?? $payload['society_name'] ?? '')),
            self::normalizedToken((string) ($payload['city'] ?? '')),
        ]));

        $base = implode('+', $segments);
        if ($base === '') {
            $base = 'property+listing';
        }

        if ($propertyId !== null) {
            return Str::limit($base, 220, '').'-('.$propertyId.')';
        }

        return Str::limit($base, 245, '');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function buildMetaTitle(string $title, array $payload): string
    {
        $humanTitle = trim((string) str_replace(['+', '-'], [' ', ' '], $title));
        $listingType = trim((string) ($payload['listing_type'] ?? ''));

        $meta = $humanTitle;
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
            (string) ($payload['locality'] ?? $payload['area'] ?? ''),
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

    private static function normalizedToken(string $value): string
    {
        $token = Str::slug($value, '-');

        return trim((string) preg_replace('/-+/', '-', $token), '-');
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
