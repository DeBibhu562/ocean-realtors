<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CreateDlfPhasePropertyAgentBlogs extends Seeder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    protected function phases(): array
    {
        return [
            2 => [
                'slug' => 'best-property-agent-in-dlf-phase-2',
                'title' => 'Best Property Agent in DLF Phase 2: Expert Help for Homes in Sectors 25 & 26',
                'excerpt' => 'Need the best property agent in DLF Phase 2? Get verified listings, society-wise pricing, and end-to-end support for buying, selling, or renting in DLF City Phase 2.',
                'meta_title' => 'Best Property Agent in DLF Phase 2 | Sectors 25 & 26',
                'meta_description' => 'Hire the best property agent in DLF Phase 2 for apartments and builder floors in Sectors 25 & 26. Transparent deals, site visits, and rental support.',
                'meta_keywords' => 'dlf phase 2 property agent, property dealer dlf phase 2, sector 25 gurgaon rent, sector 26 property, dlf city phase 2, buy flat dlf phase 2, rent apartment dlf phase 2',
                'content_file' => 'best-property-agent-in-dlf-phase-2.html',
            ],
            3 => [
                'slug' => 'best-property-agent-in-dlf-phase-3',
                'title' => 'Best Property Agent in DLF Phase 3: Buy, Sell & Rent in Sectors 24 & 25',
                'excerpt' => 'Find a trusted property agent in DLF Phase 3 who knows Sectors 24 and 25 — from rental yields and resale pricing to society transfers and furnished leases.',
                'meta_title' => 'Best Property Agent in DLF Phase 3 | Gurgaon DLF City',
                'meta_description' => 'Best property agent in DLF Phase 3 for buyers, sellers, and tenants in Sectors 24 & 25. Local market expertise and verified Gurgaon listings.',
                'meta_keywords' => 'dlf phase 3 property agent, real estate consultant dlf phase 3, sector 24 gurgaon, sector 25 property agent, rent dlf phase 3, sell apartment dlf phase 3',
                'content_file' => 'best-property-agent-in-dlf-phase-3.html',
            ],
            4 => [
                'slug' => 'best-property-agent-in-dlf-phase-4',
                'title' => 'Best Property Agent in DLF Phase 4: Property Deals Near Galleria & Arjun Marg',
                'excerpt' => 'The best property agent in DLF Phase 4 helps you buy, sell, or lease homes near Galleria Market and Arjun Marg with accurate pricing and hassle-free documentation.',
                'meta_title' => 'Best Property Agent in DLF Phase 4 | Galleria Gurgaon',
                'meta_description' => 'Connect with the best property agent in DLF Phase 4 for resale, rental, and purchase near Galleria & Arjun Marg. Verified listings and expert negotiation.',
                'meta_keywords' => 'dlf phase 4 property agent, galleria market gurgaon property, arjun marg real estate, dlf phase 4 rent, buy home dlf phase 4, property consultant galleria',
                'content_file' => 'best-property-agent-in-dlf-phase-4.html',
            ],
            5 => [
                'slug' => 'best-property-agent-in-dlf-phase-5',
                'title' => 'Best Property Agent in DLF Phase 5: Luxury Homes on Golf Course Road',
                'excerpt' => 'Looking for the best property agent in DLF Phase 5? Specialists in Sector 42 & 43 luxury apartments, golf-facing residences, and high-value sales near Golf Course Road.',
                'meta_title' => 'Best Property Agent in DLF Phase 5 | Sector 42 & 43',
                'meta_description' => 'Best property agent in DLF Phase 5 for luxury buys, sales, and leases in Sector 42 & 43. Golf Course Road expertise, NRI-friendly process, verified premium listings.',
                'meta_keywords' => 'dlf phase 5 property agent, sector 42 gurgaon property, sector 43 luxury apartment, golf course road agent, dlf camellias area, premium property gurgaon',
                'content_file' => 'best-property-agent-in-dlf-phase-5.html',
            ],
        ];
    }

    public function run(): void
    {
        $reference = BlogPost::where('slug', 'best-property-agent-in-gurgaon')->first();

        foreach ($this->phases() as $config) {
            $path = __DIR__.'/data/'.$config['content_file'];
            if (! is_readable($path)) {
                $this->command?->warn("Content file missing: {$config['content_file']}");

                continue;
            }

            $content = file_get_contents($path);
            unset($config['content_file']);

            BlogPost::updateOrCreate(
                ['slug' => $config['slug']],
                array_merge($config, [
                    'content' => $content,
                    'status' => BlogPost::STATUS_PUBLISHED,
                    'published_at' => now(),
                    'reading_time_minutes' => BlogPost::estimateReadingTime($content),
                    'user_id' => $reference?->user_id,
                    'blog_writer_id' => $reference?->blog_writer_id,
                ])
            );

            $this->command?->info("Blog ready: {$config['slug']}");
        }
    }
}
