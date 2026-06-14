<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Support\GurgaonBlogContentBuilder;
use Illuminate\Database\Seeder;

class CreateGurgaonPropertyGuideBlogs extends Seeder
{
    /** @var list<string> */
    private const IMAGES = [
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
    ];

    public function run(): void
    {
        $reference = BlogPost::where('slug', 'best-places-to-live-in-gurgaon')->first()
            ?? BlogPost::query()->published()->orderByDesc('published_at')->first();

        $index = 0;

        foreach (GurgaonBlogContentBuilder::definitions() as $definition) {
            $context = [];
            if (isset($definition['sector'])) {
                $context['sector'] = $definition['sector'];
            }
            if (isset($definition['phase'])) {
                $context['phase'] = $definition['phase'];
            }

            $content = GurgaonBlogContentBuilder::build($definition['type'], $context);
            $slug = $definition['slug'];

            BlogPost::updateOrCreate(
                ['slug' => $slug],
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

    public static function imageForSlug(string $slug): string
    {
        $images = self::IMAGES;
        $index = crc32($slug) % count($images);

        return $images[$index];
    }
}
