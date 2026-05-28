<?php

use App\Models\Agent;
use App\Models\BlogPost;
use App\Models\BlogWriter;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;

it('allows admin to create a writer safely', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.blog-writers.store'), [
        'name' => 'Safe Writer',
        'bio' => 'Profile used in blog bylines.',
    ]);

    $response->assertRedirect(route('admin.blog-writers.index'));
    $this->assertDatabaseHas('blog_writers', ['name' => 'Safe Writer']);
});

it('returns validation-style error when writer photo processing fails', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->mock(ImageService::class, function (MockInterface $mock) {
        $mock->shouldReceive('processBlogWriterPhoto')
            ->once()
            ->andThrow(new RuntimeException('Simulated image error'));
    });

    $response = $this->actingAs($admin)->from(route('admin.blog-writers.create'))->post(route('admin.blog-writers.store'), [
        'name' => 'Writer With Bad Photo',
        'bio' => 'Should fail safely.',
        'photo' => UploadedFile::fake()->image('bad-writer.jpg'),
    ]);

    $response
        ->assertRedirect(route('admin.blog-writers.create'))
        ->assertSessionHasErrors(['photo']);

    $this->assertDatabaseMissing('blog_writers', ['name' => 'Writer With Bad Photo']);
});

it('allows admin to create agent and blog without breaking public pages', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $writer = BlogWriter::factory()->create(['name' => 'Public Writer']);

    $agentCreate = $this->actingAs($admin)->post(route('admin.agents.store'), [
        'name' => 'Public Agent',
        'phone' => '+919999999999',
        'designation' => 'Property Consultant',
        'is_active' => true,
    ]);
    $agentCreate->assertRedirect(route('admin.agents.index'));

    $postCreate = $this->actingAs($admin)->post(route('admin.blog.store'), [
        'title' => 'Safety Blog Post',
        'content' => '<p>Safe content for production checks.</p>',
        'status' => BlogPost::STATUS_PUBLISHED,
        'blog_writer_id' => $writer->id,
        'noindex' => false,
    ]);
    $postCreate->assertRedirect(route('admin.blog.index'));

    $agent = Agent::query()->where('name', 'Public Agent')->firstOrFail();
    $post = BlogPost::query()->where('title', 'Safety Blog Post')->firstOrFail();

    $this->get(route('agents.index'))->assertOk();
    $this->get(route('agents.show', $agent))->assertOk();
    $this->get(route('blog.index'))->assertOk();
    $this->get(route('blog.show', $post))->assertOk();
});
