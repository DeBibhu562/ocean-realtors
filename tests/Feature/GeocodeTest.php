<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.google_maps.api_key' => 'test-google-key']);
});

it('requires admin for reverse geocode', function () {
    $response = $this->postJson('/dashboard/api/geocode/reverse', [
        'lat' => 28.4595,
        'lng' => 77.0266,
    ]);

    $response->assertUnauthorized();
});

it('reverse geocodes coordinates via Google API', function () {
    Http::fake([
        'maps.googleapis.com/maps/api/geocode/json*' => Http::response([
            'status' => 'OK',
            'results' => [[
                'formatted_address' => 'DLF Phase 2, Gurgaon, Haryana, India',
                'place_id' => 'ChIJ_test',
                'geometry' => ['location' => ['lat' => 28.4595, 'lng' => 77.0266]],
                'address_components' => [
                    ['long_name' => 'DLF Phase 2', 'types' => ['sublocality_level_1', 'sublocality']],
                    ['long_name' => 'Gurgaon', 'types' => ['locality', 'political']],
                    ['long_name' => 'Gurgaon', 'types' => ['administrative_area_level_2', 'political']],
                    ['long_name' => 'Sector 25', 'types' => ['neighborhood', 'political']],
                    ['long_name' => 'Sector Road', 'types' => ['route']],
                ],
            ]],
        ]),
    ]);

    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->postJson('/dashboard/api/geocode/reverse', [
        'lat' => 28.4595,
        'lng' => 77.0266,
    ]);

    $response->assertOk()
        ->assertJsonPath('city', 'Gurgaon')
        ->assertJsonPath('lat', 28.4595)
        ->assertJsonPath('lng', 77.0266);
});

it('returns areas for a city from database and curated data', function () {
    \App\Models\Property::factory()->create([
        'city' => 'Gurgaon',
        'area' => 'DLF Phase 2',
        'locality' => 'Sector 25',
    ]);

    $response = $this->getJson('/api/locations/areas?city=Gurgaon');

    $response->assertOk();
    $names = collect($response->json())->pluck('name');

    expect($names)->toContain('DLF Phase 2');
});

it('returns localities filtered by city and area', function () {
    \App\Models\Property::factory()->create([
        'city' => 'Gurgaon',
        'area' => 'DLF Phase 2',
        'locality' => 'Sector 25',
    ]);
    \App\Models\Property::factory()->create([
        'city' => 'Gurgaon',
        'area' => 'Sohna Road',
        'locality' => 'Sector 48',
    ]);

    $response = $this->getJson('/api/locations/localities?city=Gurgaon&area=DLF+Phase+2');

    $response->assertOk();
    $names = collect($response->json())->pluck('name');

    expect($names)->toContain('Sector 25');
    expect($names)->not->toContain('Sector 48');
});
