<?php

namespace App\Support;

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
