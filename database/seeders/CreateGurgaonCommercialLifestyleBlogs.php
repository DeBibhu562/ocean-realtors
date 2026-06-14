<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Support\GurgaonCommercialLifestyleBlogBuilder;
use Illuminate\Database\Seeder;

class CreateGurgaonCommercialLifestyleBlogs extends Seeder
{
    public function run(): void
    {
        $reference = BlogPost::where('slug', 'best-places-to-live-in-gurgaon')->first()
            ?? BlogPost::query()->published()->orderByDesc('published_at')->first();

        $index = 0;

        foreach (GurgaonCommercialLifestyleBlogBuilder::definitions() as $definition) {
            $content = GurgaonCommercialLifestyleBlogBuilder::build($definition);

            BlogPost::updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'excerpt' => $definition['excerpt'],
                    'content' => $content,
                    'meta_title' => $definition['meta_title'],
                    'meta_description' => $definition['meta_description'],
                    'meta_keywords' => $definition['meta_keywords'],
                    'status' => BlogPost::STATUS_PUBLISHED,
                    'published_at' => now()->subMinutes($index),
                    'reading_time_minutes' => BlogPost::estimateReadingTime($content),
                    'user_id' => $reference?->user_id,
                    'blog_writer_id' => $reference?->blog_writer_id,
                ]
            );

            $index++;
        }

        $this->command?->call('blog:attach-featured-images');
    }
}
