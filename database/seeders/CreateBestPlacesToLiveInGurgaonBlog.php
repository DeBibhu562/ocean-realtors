<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CreateBestPlacesToLiveInGurgaonBlog extends Seeder
{
    public function run(): void
    {
        $contentPath = __DIR__.'/data/best-places-to-live-in-gurgaon.html';
        $content = file_get_contents($contentPath);

        $reference = BlogPost::where('slug', 'best-property-agent-in-dlf-phase-1')->first()
            ?? BlogPost::query()->published()->orderByDesc('published_at')->first();

        $data = [
            'title' => 'Best Places to Live in Gurgaon: A Complete Locality Guide for Families and Professionals',
            'slug' => 'best-places-to-live-in-gurgaon',
            'excerpt' => 'Discover the best places to live in Gurgaon — from DLF City and Golf Course Road to Dwarka Expressway and Old Gurgaon sectors. Compare localities by commute, budget, schools, and lifestyle.',
            'content' => $content,
            'meta_title' => 'Best Places to Live in Gurgaon (2026 Locality Guide)',
            'meta_description' => 'Best places to live in Gurgaon for families and professionals — DLF phases, Golf Course Road, South City, Dwarka Expressway, Palam Vihar, and more. Expert locality guide by Ocean Realtors.',
            'meta_keywords' => 'best places to live in gurgaon, best locality in gurgaon, where to live in gurgaon, dlf city gurgaon, golf course road gurgaon, dwarka expressway gurgaon, south city gurgaon, gurgaon neighbourhood guide',
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
