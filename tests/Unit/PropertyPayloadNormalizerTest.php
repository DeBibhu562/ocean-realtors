<?php

namespace Tests\Unit;

use App\Support\PropertyPayloadNormalizer;
use PHPUnit\Framework\TestCase;

class PropertyPayloadNormalizerTest extends TestCase
{
    public function test_sell_listing_with_blank_maintenance_type_defaults_to_na(): void
    {
        $payload = PropertyPayloadNormalizer::forStorage([
            'listing_type' => 'Sell',
            'maintenance_type' => '',
            'price' => 5000000,
        ]);

        $this->assertSame('N/A', $payload['maintenance_type']);
    }

    public function test_rent_listing_with_blank_maintenance_type_defaults_to_separate(): void
    {
        $payload = PropertyPayloadNormalizer::forStorage([
            'listing_type' => 'Rent',
            'maintenance_type' => null,
            'price' => 50000,
        ]);

        $this->assertSame('Separate', $payload['maintenance_type']);
    }

    public function test_blank_parking_fields_default_to_zero_string(): void
    {
        $payload = PropertyPayloadNormalizer::forStorage([
            'listing_type' => 'Rent',
            'covered_parking' => '',
            'open_parking' => null,
        ]);

        $this->assertSame('0', $payload['covered_parking']);
        $this->assertSame('0', $payload['open_parking']);
    }

    public function test_negative_floor_values_are_clamped_to_zero(): void
    {
        $payload = PropertyPayloadNormalizer::forStorage([
            'floor_no' => -10,
            'total_floors' => -16,
        ]);

        $this->assertSame(0, $payload['floor_no']);
        $this->assertSame(0, $payload['total_floors']);
    }

    public function test_canonical_title_is_human_readable(): void
    {
        $title = PropertyPayloadNormalizer::buildCanonicalTitle([
            'bhk' => '3 BHK',
            'type' => 'Builder Floor',
            'locality' => 'DLF PHASE 2',
            'city' => 'Gurgaon',
        ]);

        $this->assertSame('3 BHK Builder Floor in DLF Phase 2, Gurgaon', $title);
    }

    public function test_listing_slug_uses_hyphenated_segments_and_property_id(): void
    {
        $payload = [
            'bhk' => '3 BHK',
            'type' => 'Builder Floor',
            'locality' => 'DLF Phase 2',
            'city' => 'Gurgaon',
        ];

        $slug = PropertyPayloadNormalizer::buildListingSlug($payload, 50);

        $this->assertSame('3-bhk-builder-floor-in-dlf-phase-2-gurgaon-50', $slug);
    }

    public function test_legacy_machine_title_is_detected(): void
    {
        $this->assertTrue(PropertyPayloadNormalizer::usesLegacyMachineTitle('3-bhk+builder-floor+dlf-phase-2+gurgaon-(50)'));
        $this->assertFalse(PropertyPayloadNormalizer::usesLegacyMachineTitle('3 BHK Builder Floor in DLF Phase 2, Gurgaon'));
    }
}
