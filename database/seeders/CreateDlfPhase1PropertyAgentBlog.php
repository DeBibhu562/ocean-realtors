<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CreateDlfPhase1PropertyAgentBlog extends Seeder
{
    public function run(): void
    {
        $contentPath = __DIR__.'/data/best-property-agent-in-dlf-phase-1.html';
        $content = file_get_contents($contentPath);

        $reference = BlogPost::where('slug', 'best-property-agent-in-gurgaon')->first();

        $data = [
            'title' => 'Best Property Agent in DLF Phase 1: Your Trusted Partner for Buying, Selling, and Renting Property',
            'slug' => 'best-property-agent-in-dlf-phase-1',
            'excerpt' => 'Looking for the best property agent in DLF Phase 1? Learn how experienced consultants help buyers, sellers, and tenants navigate Sectors 26 & 27 with verified listings and local expertise.',
            'content' => $content,
            'meta_title' => 'Best Property Agent in DLF Phase 1 | Buy, Sell & Rent',
            'meta_description' => 'Find the best property agent in DLF Phase 1 for buying, selling, and renting in Sectors 26 & 27. Expert guidance, verified listings, and smooth transactions.',
            'meta_keywords' => 'dlf phase 1 property agent, best property dealer dlf phase 1, real estate consultant dlf phase 1, sector 26 gurgaon property, sector 27 gurgaon, rent property dlf phase 1, buy apartment dlf phase 1, dlf city phase 1',
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now(),
            'reading_time_minutes' => BlogPost::estimateReadingTime($content),
            'user_id' => $reference?->user_id,
            'blog_writer_id' => $reference?->blog_writer_id,
        ];

        BlogPost::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );
    }
}
