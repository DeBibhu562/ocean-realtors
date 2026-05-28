<?php

namespace App\Support;

use Illuminate\Support\Str;

final class LocationDataLoader
{
    /** @var array<string, string> */
    private const CITY_ALIASES = [
        'gurugram' => 'Gurgaon',
        'gurgaon' => 'Gurgaon',
        'delhi' => 'New Delhi',
        'new delhi' => 'New Delhi',
        'new-delhi' => 'New Delhi',
        'greater noida' => 'Greater Noida',
        'greater-noida' => 'Greater Noida',
        'bengaluru' => 'Bengaluru',
        'bangalore' => 'Bengaluru',
        'bombay' => 'Mumbai',
    ];

    /**
     * @return list<string>
     */
    public static function curatedCities(): array
    {
        $path = resource_path('data/locations/cities.json');
        if (! is_file($path)) {
            return ['Gurgaon', 'New Delhi', 'Noida', 'Delhi', 'Faridabad', 'Ghaziabad'];
        }

        $data = json_decode((string) file_get_contents($path), true);
        if (! is_array($data)) {
            return [];
        }

        return array_values(array_unique(array_map(
            fn (string $name) => self::canonicalCity($name),
            array_filter(array_map('strval', $data)),
        )));
    }

    public static function canonicalCity(string $name): string
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            return '';
        }

        $key = strtolower($trimmed);

        return self::CITY_ALIASES[$key] ?? $trimmed;
    }

    /**
     * @return array{areas: list<string>, localities: array<string, list<string>>}
     */
    public static function curatedForCity(string $city): array
    {
        $slug = Str::slug(self::canonicalCity($city));
        if ($slug === '') {
            return ['areas' => [], 'localities' => []];
        }

        $path = resource_path('data/locations/'.$slug.'.json');
        if (! is_file($path)) {
            return ['areas' => [], 'localities' => []];
        }

        $data = json_decode((string) file_get_contents($path), true);
        if (! is_array($data)) {
            return ['areas' => [], 'localities' => []];
        }

        $areas = array_values(array_filter(array_map('strval', $data['areas'] ?? [])));
        $localities = [];
        foreach ($data['localities'] ?? [] as $area => $items) {
            if (! is_array($items)) {
                continue;
            }
            $localities[(string) $area] = array_values(array_filter(array_map('strval', $items)));
        }

        return ['areas' => $areas, 'localities' => $localities];
    }
}
