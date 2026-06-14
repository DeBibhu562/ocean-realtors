<?php

use App\Models\Agent;
use App\Models\Lead;

it('creates an agent profile lead with correct agent attribution', function () {
    $agent = Agent::factory()->create([
        'is_active' => true,
        'phone' => '+918743859120',
        'whatsapp_phone' => '+918743859120',
    ]);

    $response = $this->postJson('/api/leads', [
        'agent_id' => $agent->id,
        'name' => 'Profile Visitor',
        'phone' => '+919876543210',
        'email' => 'visitor@example.com',
        'is_real_estate_agent' => false,
        'message' => 'Agent profile contact (Phone call): '.$agent->name,
        'contact_channel' => 'call',
        'source' => 'web',
    ]);

    $response->assertOk()->assertJson([
        'success' => true,
        'contact' => [
            'phone' => '+918743859120',
            'whatsapp_phone' => '+918743859120',
        ],
    ]);

    $lead = Lead::query()->where('name', 'Profile Visitor')->first();

    expect($lead)->not->toBeNull()
        ->and($lead->agent_id)->toBe($agent->id)
        ->and($lead->property_id)->toBeNull()
        ->and($lead->contact_channel)->toBe('call')
        ->and($lead->message)->toContain('Contact preference: Phone call');
});

it('returns contact details for agent profile whatsapp leads', function () {
    $agent = Agent::factory()->create([
        'is_active' => true,
        'phone' => '+919810000001',
        'whatsapp_phone' => '+919810000099',
    ]);

    $response = $this->postJson('/api/leads', [
        'agent_id' => $agent->id,
        'name' => 'WhatsApp Visitor',
        'phone' => '+919876543211',
        'email' => 'wa@example.com',
        'is_real_estate_agent' => true,
        'contact_channel' => 'whatsapp',
    ]);

    $response->assertOk()->assertJsonPath('contact.phone', '+919810000001')
        ->assertJsonPath('contact.whatsapp_phone', '+919810000099');

    $lead = Lead::query()->where('name', 'WhatsApp Visitor')->first();

    expect($lead->agent_id)->toBe($agent->id)
        ->and($lead->contact_channel)->toBe('whatsapp');
});

it('does not return contact details for property leads', function () {
    $user = \App\Models\User::factory()->create();
    $agent = Agent::factory()->create(['is_active' => true]);
    $property = \App\Models\Property::factory()->create([
        'user_id' => $user->id,
        'agent_id' => $agent->id,
        'slug' => 'agent-lead-property-'.uniqid(),
    ]);

    $response = $this->postJson('/api/leads', [
        'property_id' => $property->id,
        'name' => 'Property Only Lead',
        'phone' => '+919876543212',
        'email' => 'prop@example.com',
        'contact_channel' => 'call',
    ]);

    $response->assertOk()->assertJsonMissing(['contact']);
});

it('rejects inactive agent profile leads', function () {
    $agent = Agent::factory()->create(['is_active' => false]);

    $response = $this->postJson('/api/leads', [
        'agent_id' => $agent->id,
        'name' => 'Blocked Visitor',
        'phone' => '+919876543213',
        'email' => 'blocked@example.com',
        'contact_channel' => 'call',
    ]);

    $response->assertNotFound();
});

it('strips phone numbers from agent show page payload', function () {
    $agent = Agent::factory()->create([
        'is_active' => true,
        'slug' => 'test-agent-profile-'.uniqid(),
        'phone' => '+918743859120',
    ]);

    $response = $this->get(route('agents.show', $agent->slug));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Agents/Show')
        ->has('agent', fn ($agentPage) => $agentPage
            ->where('id', $agent->id)
            ->where('has_contact', true)
            ->missing('phone')
            ->missing('whatsapp_phone')
        )
    );
});
