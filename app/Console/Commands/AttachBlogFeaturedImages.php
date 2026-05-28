<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AttachBlogFeaturedImages extends Command
{
    protected $signature = 'blog:attach-featured-images';

    protected $description = 'Download and attach featured images to seeded blog posts';

    /**
     * @var array<string, string>
     */
    protected array $slugImages = [
        'top-localities-rent-gurgaon-2026' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
        'first-time-home-buyer-checklist-ncr' => 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&w=1200&q=80',
        'commercial-office-golf-course-road-trends' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
        'verify-property-listing-before-visit' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
    ];

    public function handle(ImageService $imageService): int
    {
        foreach ($this->slugImages as $slug => $url) {
            $post = BlogPost::where('slug', $slug)->first();

            if (! $post) {
                $this->warn("Post not found: {$slug}");

                continue;
            }

            if ($post->featured_image && str_contains($post->featured_image, '/storage/blog/')) {
                $this->line("Skipping {$slug} — already has featured image.");

                continue;
            }

            $response = Http::timeout(60)->get($url);

            if (! $response->successful()) {
                $this->error("Failed to download image for {$slug}");

                continue;
            }

            $tmp = tempnam(sys_get_temp_dir(), 'blog_img_');
            file_put_contents($tmp, $response->body());

            try {
                $path = $imageService->processBlogFeaturedImage($tmp);
                $post->update(['featured_image' => $path]);
                $this->info("Attached featured image to: {$slug}");
            } finally {
                @unlink($tmp);
            }
        }

        return self::SUCCESS;
    }
}
