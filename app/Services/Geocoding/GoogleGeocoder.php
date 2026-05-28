<?php

namespace App\Services\Geocoding;

use App\Support\LocationDataLoader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleGeocoder implements GeocoderInterface
{
    protected const NCR_LAT = 28.4595;

    protected const NCR_LNG = 77.0266;

    public function reverseGeocode(float $lat, float $lng): array
    {
        $cacheKey = 'geocode.reverse.'.round($lat, 4).'.'.round($lng, 4);

        return Cache::remember($cacheKey, $this->cacheTtl(), function () use ($lat, $lng) {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => $lat.','.$lng,
                'key' => $this->apiKey(),
                'language' => 'en',
                'region' => 'in',
            ]);

            $payload = $response->json();
            if (! $response->successful() || ($payload['status'] ?? '') !== 'OK' || empty($payload['results'][0])) {
                throw new RuntimeException($this->formatGoogleError('Reverse geocode failed', $payload, $response->status()));
            }

            return $this->normalizeGeocodeResult($payload['results'][0]);
        });
    }

    public function searchPlaces(string $query, ?string $city = null): array
    {
        $trimmed = trim($query);
        if (strlen($trimmed) < 3) {
            return [];
        }

        $cacheKey = 'geocode.search.'.md5(strtolower($trimmed).'|'.strtolower(trim((string) $city)));

        return Cache::remember($cacheKey, $this->cacheTtl(), function () use ($trimmed, $city) {
            $params = [
                'input' => $city ? $trimmed.', '.$city.', India' : $trimmed,
                'key' => $this->apiKey(),
                'components' => 'country:in',
                'language' => 'en',
            ];

            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/place/autocomplete/json', $params);
            $payload = $response->json();

            if (! $response->successful() || ! in_array($payload['status'] ?? '', ['OK', 'ZERO_RESULTS'], true)) {
                throw new RuntimeException($this->formatGoogleError('Place search failed', $payload, $response->status()));
            }

            return collect($payload['predictions'] ?? [])
                ->take(8)
                ->map(fn (array $item) => [
                    'description' => (string) ($item['description'] ?? ''),
                    'place_id' => (string) ($item['place_id'] ?? ''),
                    'lat' => null,
                    'lng' => null,
                ])
                ->values()
                ->all();
        });
    }

    public function placeDetails(string $placeId): array
    {
        $placeId = trim($placeId);
        if ($placeId === '') {
            throw new RuntimeException('Place id is required.');
        }

        $cacheKey = 'geocode.place.'.md5($placeId);

        return Cache::remember($cacheKey, $this->cacheTtl(), function () use ($placeId) {
            $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'key' => $this->apiKey(),
                'fields' => 'place_id,formatted_address,geometry,address_component,name,types',
                'language' => 'en',
            ]);

            $payload = $response->json();
            if (! $response->successful() || ($payload['status'] ?? '') !== 'OK' || empty($payload['result'])) {
                throw new RuntimeException($this->formatGoogleError('Place details failed', $payload, $response->status()));
            }

            $result = $payload['result'];
            $lat = (float) ($result['geometry']['location']['lat'] ?? 0);
            $lng = (float) ($result['geometry']['location']['lng'] ?? 0);

            $normalized = $this->parseAddressComponents($result['address_components'] ?? []);
            $normalized['lat'] = $lat;
            $normalized['lng'] = $lng;
            $normalized['formatted_address'] = (string) ($result['formatted_address'] ?? '');
            $normalized['place_id'] = (string) ($result['place_id'] ?? $placeId);

            if (in_array('premise', $result['types'] ?? [], true) || in_array('establishment', $result['types'] ?? [], true)) {
                $normalized['society_name'] = $normalized['society_name'] ?? (string) ($result['name'] ?? null);
            }

            return $normalized;
        });
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array{
     *   lat: float,
     *   lng: float,
     *   formatted_address: string,
     *   city: ?string,
     *   area: ?string,
     *   locality: ?string,
     *   address_line: ?string,
     *   place_id: ?string,
     *   society_name: ?string,
     *   landmark: ?string
     * }
     */
    protected function normalizeGeocodeResult(array $result): array
    {
        $lat = (float) ($result['geometry']['location']['lat'] ?? 0);
        $lng = (float) ($result['geometry']['location']['lng'] ?? 0);

        $normalized = $this->parseAddressComponents($result['address_components'] ?? []);
        $normalized['lat'] = $lat;
        $normalized['lng'] = $lng;
        $normalized['formatted_address'] = (string) ($result['formatted_address'] ?? '');
        $normalized['place_id'] = (string) ($result['place_id'] ?? null);

        if (($normalized['address_line'] ?? '') === '' && $normalized['formatted_address'] !== '') {
            $normalized['address_line'] = $normalized['formatted_address'];
        }

        return $normalized;
    }

    /**
     * @param  list<array<string, mixed>>  $components
     * @return array{
     *   lat: float,
     *   lng: float,
     *   formatted_address: string,
     *   city: ?string,
     *   area: ?string,
     *   locality: ?string,
     *   address_line: ?string,
     *   place_id: ?string,
     *   society_name: ?string,
     *   landmark: ?string
     * }
     */
    protected function parseAddressComponents(array $components): array
    {
        $byType = [];
        foreach ($components as $component) {
            foreach ($component['types'] ?? [] as $type) {
                $byType[$type] = (string) ($component['long_name'] ?? $component['short_name'] ?? '');
            }
        }

        $city = $this->firstNonEmpty($byType, [
            'locality',
            'administrative_area_level_3',
            'administrative_area_level_2',
        ]);

        if ($city === null) {
            $state = $this->firstNonEmpty($byType, ['administrative_area_level_1']);
            if ($state !== null && strcasecmp($state, 'Delhi') === 0) {
                $city = 'New Delhi';
            }
        }

        $city = $city !== null ? LocationDataLoader::canonicalCity($city) : null;

        $area = $this->firstNonEmpty($byType, [
            'sublocality_level_1',
            'administrative_area_level_3',
            'neighborhood',
        ]);

        if ($area !== null && $city !== null && strcasecmp($area, $city) === 0) {
            $area = null;
        }

        $locality = $this->firstNonEmpty($byType, [
            'sublocality_level_2',
            'sublocality',
            'neighborhood',
        ]);

        if ($locality !== null && $area !== null && strcasecmp($locality, $area) === 0) {
            $locality = $this->firstNonEmpty($byType, ['sublocality_level_2', 'neighborhood']);
        }

        $addressParts = array_filter([
            $byType['premise'] ?? null,
            $byType['subpremise'] ?? null,
            $byType['street_number'] ?? null,
            $byType['route'] ?? null,
            $byType['postal_code'] ?? null,
        ]);

        $society = $this->firstNonEmpty($byType, ['premise', 'establishment', 'point_of_interest']);

        return [
            'lat' => 0.0,
            'lng' => 0.0,
            'formatted_address' => '',
            'city' => $city,
            'area' => $area,
            'locality' => $locality,
            'address_line' => $addressParts !== [] ? implode(', ', $addressParts) : null,
            'place_id' => null,
            'society_name' => $society,
            'landmark' => $this->firstNonEmpty($byType, ['point_of_interest', 'establishment']),
        ];
    }

    /**
     * @param  array<string, string>  $byType
     * @param  list<string>  $types
     */
    protected function firstNonEmpty(array $byType, array $types): ?string
    {
        foreach ($types as $type) {
            $value = trim($byType[$type] ?? '');
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>|null  $payload
     */
    protected function formatGoogleError(string $context, ?array $payload, int $httpStatus): string
    {
        $status = (string) ($payload['status'] ?? $httpStatus);
        $detail = trim((string) ($payload['error_message'] ?? ''));

        if ($status === 'REQUEST_DENIED' && str_contains(strtolower($detail), 'not activated')) {
            return 'Enable Geocoding API and Places API on your Google Cloud project, then retry. Billing must be active on the project.';
        }

        if ($status === 'REQUEST_DENIED') {
            return 'Google rejected this request (REQUEST_DENIED). Check API key restrictions: server calls need your server IP allowed, with Geocoding API and Places API enabled.';
        }

        return $detail !== '' ? "{$context}: {$detail}" : "{$context}: {$status}";
    }

    protected function apiKey(): string
    {
        $key = trim((string) config('services.google_maps.api_key', ''));
        if ($key === '') {
            throw new RuntimeException('GOOGLE_MAPS_API_KEY is not configured.');
        }

        return $key;
    }

    protected function cacheTtl(): \DateInterval|\DateTimeInterface|int|null
    {
        $days = max(1, (int) config('services.google_maps.geocode_cache_days', 30));

        return now()->addDays($days);
    }
}
