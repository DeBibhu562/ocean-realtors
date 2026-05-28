<?php

use App\Models\Property;

it('returns empty suggestions when query is shorter than 3 characters', function () {
    Property::factory()->create(['city' => 'Gurgaon', 'address' => 'Sector 14']);

    $response = $this->getJson('/api/locations?q=ab');

    $response->assertOk()->assertExactJson([]);
});

it('returns only locations that have property listings', function () {
    Property::factory()->create([
        'city' => 'Gurgaon',
        'society_name' => 'DLF Phase 2',
        'address' => 'Sector 25',
    ]);
    Property::factory()->create([
        'city' => 'Delhi',
        'address' => 'Connaught Place',
    ]);

    $response = $this->getJson('/api/locations?q=Gur');

    $response->assertOk();
    $data = $response->json();

    expect($data)->not->toBeEmpty();
    expect(collect($data)->pluck('name'))->toContain('Gurgaon');
    expect(collect($data)->pluck('name'))->not->toContain('Mumbai');
    expect($data[0])->toHaveKeys(['name', 'type', 'subtitle', 'city', 'count']);
});

it('lists areas for a city merging curated and database values', function () {
    Property::factory()->create([
        'city' => 'Gurgaon',
        'area' => 'Custom Zone',
        'locality' => 'Block A',
    ]);

    $response = $this->getJson('/api/locations/areas?city=Gurgaon');

    $response->assertOk();
    $names = collect($response->json())->pluck('name');

    expect($names)->toContain('DLF Phase 2', 'Custom Zone');
});

it('lists cities from database for city dropdown', function () {
    Property::factory()->count(2)->create(['city' => 'Gurgaon']);
    Property::factory()->create(['city' => 'Noida']);

    $response = $this->getJson('/api/locations/cities');

    $response->assertOk();
    $names = collect($response->json())->pluck('name');

    expect($names)->toContain('Gurgaon', 'Noida');
    expect($names)->not->toContain('Mumbai');
});
