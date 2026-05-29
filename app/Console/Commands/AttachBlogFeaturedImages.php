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
        'best-property-agent-in-dlf-phase-1' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-dlf-phase-2' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-dlf-phase-3' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-dlf-phase-4' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-dlf-phase-5' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-dlf-alameda' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-dlf-gardencity' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-sushant-lok-1' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-sushant-lok-2' => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-sushant-lok-3' => 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-south-city-1' => 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-south-city-2' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-palam-vihar' => 'https://images.unsplash.com/photo-1600047509358-9dc75507daeb?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-new-palam-vihar' => 'https://images.unsplash.com/photo-1600585154363-67eb9e2e2099?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-udyog-vihar-phase-1' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-udyog-vihar-phase-2' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-udyog-vihar-phase-3' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-udyog-vihar-phase-4' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-udyog-vihar-phase-5' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-cyber-city' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-cyber-hub' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-mg-road' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-golf-course-road' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80',
        'best-property-agent-in-golf-course-extension-road' => 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
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
