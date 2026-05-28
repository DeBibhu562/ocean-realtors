<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\BlogWriter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'user_id' => User::factory(),
            'blog_writer_id' => BlogWriter::factory(),
            'title' => $title,
            'slug' => BlogPost::uniqueSlug($title),
            'excerpt' => fake()->paragraph(2),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(4)).'</p>',
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
            'reading_time_minutes' => fake()->numberBetween(2, 8),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}
