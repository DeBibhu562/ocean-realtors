<?php

namespace App\Support;

/**
 * Computes completion_score (0–100) from listing payload using the same visibility rules as the Vue registry.
 */
final class ListingCompletionScore
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function calculate(array $data, bool $intentPublish = true): int
    {
        if (! $intentPublish) {
            return self::draftHeuristicScore($data);
        }

        $path = base_path('docs/listing-field-registry.json');
        if (! is_readable($path)) {
            return 0;
        }

        $json = json_decode((string) file_get_contents($path), true);
        if (! is_array($json) || ! isset($json['fields']) || ! is_array($json['fields'])) {
            return 0;
        }

        $ctx = self::contextFromData($data);
        $total = 0;
        $done = 0;

        foreach ($json['fields'] as $field) {
            if (! is_array($field) || ! isset($field['id'], $field['dbColumn'])) {
                continue;
            }

            if (! self::matchesPredicates($field['showWhen'] ?? null, $ctx, true)) {
                continue;
            }

            $required = self::matchesPredicates($field['requiredWhen'] ?? null, $ctx, false);
            if (! $required) {
                continue;
            }

            $total++;
            if (self::isSatisfied($field['dbColumn'], $data)) {
                $done++;
            }
        }

        if ($total === 0) {
            return 0;
        }

        return (int) round(($done / $total) * 100);
    }

    /**
     * Lightweight progress for draft saves (registry uses draft_publish predicates).
     *
     * @param  array<string, mixed>  $data
     */
    private static function draftHeuristicScore(array $data): int
    {
        $keys = ['title', 'city', 'address', 'category', 'listing_type', 'type', 'price', 'description', 'agent_id'];
        $filled = 0;
        foreach ($keys as $k) {
            $v = $data[$k] ?? null;
            if ($v === null) {
                continue;
            }
            if (is_numeric($v) && (float) $v === 0.0 && $k === 'price') {
                continue;
            }
            if (is_string($v) && trim($v) === '') {
                continue;
            }
            if ($v === '') {
                continue;
            }
            $filled++;
        }

        return (int) round($filled / count($keys) * 100);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function contextFromData(array $data): array
    {
        return [
            'category' => (string) ($data['category'] ?? ''),
            'listing_type' => (string) ($data['listing_type'] ?? ''),
            'user_role' => (string) ($data['user_role'] ?? ''),
            'listing_status' => (string) ($data['listing_status'] ?? ''),
            'property_type' => (string) ($data['type'] ?? ''),
            'draft_publish' => (bool) ($data['_completion_publish_mode'] ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $ctx
     */
    /**
     * @param  bool  $emptyMeansMatch  true for showWhen (always show); false for requiredWhen (optional)
     */
    private static function matchesPredicates(mixed $when, array $ctx, bool $emptyMeansMatch = true): bool
    {
        if ($when === null || $when === []) {
            return $emptyMeansMatch;
        }

        if (! is_array($when)) {
            return $emptyMeansMatch;
        }

        $all = $when['all'] ?? [];
        $any = $when['any'] ?? [];

        if (is_array($all) && is_array($any) && $all === [] && $any === []) {
            return $emptyMeansMatch;
        }

        if (is_array($all)) {
            foreach ($all as $p) {
                if (! self::evalPredicate((string) $p, $ctx)) {
                    return false;
                }
            }
        }

        if (is_array($any) && $any !== []) {
            $anyMatch = false;
            foreach ($any as $p) {
                if (self::evalPredicate((string) $p, $ctx)) {
                    $anyMatch = true;
                    break;
                }
            }
            if (! $anyMatch) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $ctx
     */
    private static function evalPredicate(string $p, array $ctx): bool
    {
        $neg = str_starts_with($p, '!');
        if ($neg) {
            $p = substr($p, 1);
        }

        if ($p === 'draft_publish') {
            return ! empty($ctx['draft_publish']);
        }

        if (! str_contains($p, ':')) {
            return ! $neg;
        }

        [$k, $v] = explode(':', $p, 2);
        $actual = (string) ($ctx[$k] ?? '');
        $match = $actual === $v;

        return $neg ? ! $match : $match;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function isSatisfied(string $dbColumn, array $data): bool
    {
        if (! array_key_exists($dbColumn, $data)) {
            return false;
        }

        $val = $data[$dbColumn];

        if ($dbColumn === 'photos') {
            return is_array($val) && count(array_filter($val, fn ($x) => $x !== null && $x !== '')) >= 3;
        }

        if ($dbColumn === 'amenities' || $dbColumn === 'furnishing_items' || $dbColumn === 'highlights') {
            return is_array($val) && count($val) > 0;
        }

        if (is_bool($val)) {
            return true;
        }

        if ($val === null) {
            return false;
        }

        if (is_string($val)) {
            return trim($val) !== '';
        }

        if (is_numeric($val)) {
            return true;
        }

        return $val !== '';
    }
}
