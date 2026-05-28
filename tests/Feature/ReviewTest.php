<?php

use App\Models\BlogPost;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a guest review as pending', function () {
    $property = Property::factory()->create();

    $response = $this->postJson('/api/reviews', [
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'rating' => 5,
        'body' => 'Excellent property and smooth process throughout.',
        'website' => '',
    ]);

    $response->assertOk()->assertJsonPath('success', true);

    $this->assertDatabaseHas('reviews', [
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'email' => 'jane@example.com',
        'status' => Review::STATUS_PENDING,
    ]);
});

it('rejects honeypot submissions', function () {
    $property = Property::factory()->create();

    $this->postJson('/api/reviews', [
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'name' => 'Spam Bot',
        'email' => 'spam@example.com',
        'rating' => 5,
        'body' => 'This should be rejected by the honeypot field.',
        'website' => 'http://spam.test',
    ])->assertStatus(422);
});

it('lists only approved reviews publicly', function () {
    $property = Property::factory()->create();

    Review::query()->create([
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'name' => 'Pending User',
        'email' => 'pending@example.com',
        'rating' => 4,
        'body' => 'Still waiting for approval on this review.',
        'status' => Review::STATUS_PENDING,
    ]);

    Review::query()->create([
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'name' => 'Approved User',
        'email' => 'approved@example.com',
        'rating' => 5,
        'body' => 'Approved and visible to everyone on the site.',
        'status' => Review::STATUS_APPROVED,
        'approved_at' => now(),
    ]);

    $response = $this->getJson('/api/reviews?'.http_build_query([
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
    ]));

    $response->assertOk()
        ->assertJsonPath('stats.total_count', 1)
        ->assertJsonCount(1, 'reviews')
        ->assertJsonPath('reviews.0.name', 'Approved User');
});

it('validates property target exists', function () {
    $this->postJson('/api/reviews', [
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => 99999,
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'rating' => 5,
        'body' => 'This should fail because the property does not exist.',
        'website' => '',
    ])->assertNotFound();
});

it('allows admin to approve a review', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $review = Review::query()->create([
        'target_type' => Review::TARGET_SITE,
        'target_id' => null,
        'name' => 'Site Guest',
        'email' => 'guest@example.com',
        'rating' => 5,
        'body' => 'Great experience with Ocean Realtors overall.',
        'status' => Review::STATUS_PENDING,
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.reviews.approve', $review))
        ->assertRedirect();

    expect($review->fresh())
        ->status->toBe(Review::STATUS_APPROVED)
        ->approved_at->not->toBeNull()
        ->approved_by->toBe($admin->id);
});

it('blocks duplicate submissions within 24 hours', function () {
    $property = Property::factory()->create();

    Review::query()->create([
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'name' => 'Repeat User',
        'email' => 'repeat@example.com',
        'rating' => 4,
        'body' => 'First review submitted earlier today.',
        'status' => Review::STATUS_PENDING,
        'created_at' => now(),
    ]);

    $this->postJson('/api/reviews', [
        'target_type' => Review::TARGET_PROPERTY,
        'target_id' => $property->id,
        'name' => 'Repeat User',
        'email' => 'repeat@example.com',
        'rating' => 5,
        'body' => 'Attempting to submit another review too soon.',
        'website' => '',
    ])->assertStatus(422);
});

it('accepts site reviews without target id', function () {
    $this->postJson('/api/reviews', [
        'target_type' => Review::TARGET_SITE,
        'name' => 'Happy Client',
        'email' => 'client@example.com',
        'rating' => 5,
        'body' => 'Ocean Realtors made our home search stress-free.',
        'website' => '',
    ])->assertOk();

    $this->assertDatabaseHas('reviews', [
        'target_type' => Review::TARGET_SITE,
        'target_id' => null,
        'status' => Review::STATUS_PENDING,
    ]);
});

it('requires published blog post for blog reviews', function () {
    $draft = BlogPost::factory()->create(['status' => BlogPost::STATUS_DRAFT]);

    $this->postJson('/api/reviews', [
        'target_type' => Review::TARGET_BLOG_POST,
        'target_id' => $draft->id,
        'name' => 'Reader',
        'email' => 'reader@example.com',
        'rating' => 4,
        'body' => 'Trying to review a draft blog post should not work.',
        'website' => '',
    ])->assertNotFound();
});
