<?php

use App\Models\Agent;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;

it('creates a chatbot lead without a property', function () {
    $agent = Agent::factory()->create(['slug' => 'ocean-realtors-team', 'is_active' => true]);

    $response = $this->postJson('/api/leads', [
        'name' => 'Chatbot User',
        'phone' => '+919990633797',
        'email' => 'chat@example.com',
        'intent' => 'rent',
        'city' => 'Gurgaon',
        'source' => 'chatbot',
    ]);

    $response->assertOk()->assertJson(['success' => true]);

    $lead = Lead::query()->where('name', 'Chatbot User')->first();

    expect($lead)->not->toBeNull()
        ->and($lead->source)->toBe('chatbot')
        ->and($lead->property_id)->toBeNull()
        ->and($lead->agent_id)->toBe($agent->id)
        ->and($lead->message)->toContain('Looking to rent');
});

it('still creates a lead with a property id', function () {
    $user = User::factory()->create();
    $agent = Agent::factory()->create(['is_active' => true]);
    $property = Property::factory()->create([
        'user_id' => $user->id,
        'agent_id' => $agent->id,
        'slug' => 'test-property-slug-'.uniqid(),
    ]);

    $response = $this->postJson('/api/leads', [
        'property_id' => $property->id,
        'name' => 'Property Lead',
        'phone' => '+919876543210',
        'email' => 'prop@example.com',
        'message' => 'Interested',
    ]);

    $response->assertOk();

    expect(Lead::query()->where('name', 'Property Lead')->value('property_id'))->toBe($property->id);
});

it('records quick contact channel on property enquiry', function () {
    $user = User::factory()->create();
    $agent = Agent::factory()->create(['is_active' => true]);
    $property = Property::factory()->create([
        'user_id' => $user->id,
        'agent_id' => $agent->id,
        'slug' => 'quick-contact-'.uniqid(),
    ]);

    $response = $this->postJson('/api/leads', [
        'property_id' => $property->id,
        'name' => 'Quick User',
        'phone' => '+919876543211',
        'message' => 'Quick whatsapp from property page: Test flat',
        'contact_channel' => 'whatsapp',
    ]);

    $response->assertOk();

    $lead = Lead::query()->where('name', 'Quick User')->first();

    expect($lead)->not->toBeNull()
        ->and($lead->message)->toContain('Contact preference: WhatsApp')
        ->and($lead->property_id)->toBe($property->id);
});

it('rejects reserved slug intent payloads on invalid data', function () {
    $response = $this->postJson('/api/leads', [
        'name' => '',
        'phone' => '123',
        'source' => 'chatbot',
    ]);

    $response->assertUnprocessable();
});
