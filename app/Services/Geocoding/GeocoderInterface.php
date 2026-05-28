<?php

namespace App\Services\Geocoding;

interface GeocoderInterface
{
    /**
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
    public function reverseGeocode(float $lat, float $lng): array;

    /**
     * @return list<array{
     *   description: string,
     *   place_id: string,
     *   lat: ?float,
     *   lng: ?float
     * }>
     */
    public function searchPlaces(string $query, ?string $city = null): array;

    /**
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
    public function placeDetails(string $placeId): array;
}
