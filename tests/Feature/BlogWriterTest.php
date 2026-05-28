<?php

use App\Models\BlogPost;
use App\Models\BlogWriter;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('shows blog writer name on public list not admin user name', function () {
    $admin = User::factory()->create(['name' => 'Admin User', 'is_admin' => true]);
    $writer = BlogWriter::factory()->create(['name' => 'Priya Sharma']);

    $post = BlogPost::factory()->published()->create([
        'user_id' => $admin->id,
        'blog_writer_id' => $writer->id,
        'title' => 'Test Article',
    ]);

    $post->load('blogWriter');
    $list = $post->toListArray();

    expect($list['author']['name'])->toBe('Priya Sharma')
        ->and($list['author']['name'])->not->toBe('Admin User');
});

it('allows admin to update a blog writer profile', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['is_admin' => true]);
    $writer = BlogWriter::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($admin)->put(route('admin.blog-writers.update', $writer), [
        'name' => 'Updated Writer',
        'bio' => 'New bio text for display.',
        'photo' => UploadedFile::fake()->image('writer.jpg', 400, 400),
    ]);

    $response->assertRedirect(route('admin.blog-writers.index'));

    $writer->refresh();
    expect($writer->name)->toBe('Updated Writer')
        ->and($writer->bio)->toBe('New bio text for display.')
        ->and($writer->photo)->not->toBeNull();
});

it('requires blog_writer_id when admin creates a blog post', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $writer = BlogWriter::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.blog.store'), [
        'title' => 'New Post Title',
        'content' => '<p>Hello world content here.</p>',
        'status' => BlogPost::STATUS_DRAFT,
        'blog_writer_id' => $writer->id,
        'noindex' => false,
    ]);

    $response->assertRedirect(route('admin.blog.index'));

    $post = BlogPost::query()->where('title', 'New Post Title')->first();
    expect($post)->not->toBeNull()
        ->and($post->blog_writer_id)->toBe($writer->id)
        ->and($post->user_id)->toBe($admin->id);
});
