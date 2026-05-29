<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CreateCommercialCorridorPropertyAgentBlogs extends Seeder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    protected function localities(): array
    {
        return [
            [
                'slug' => 'best-property-agent-in-udyog-vihar-phase-1',
                'title' => 'Best Property Agent in Udyog Vihar Phase 1: Office & Commercial Space Experts',
                'excerpt' => 'Find the best property agent in Udyog Vihar Phase 1 for office leasing, commercial purchases, and workspace solutions in Gurgaon\'s original industrial hub.',
                'meta_title' => 'Best Property Agent in Udyog Vihar Phase 1 | Gurgaon',
                'meta_description' => 'Best property agent in Udyog Vihar Phase 1 for office rent, commercial buy/sell, and fit-out guidance. Verified listings and local market expertise.',
                'meta_keywords' => 'udyog vihar phase 1 property agent, office space udyog vihar phase 1, commercial property gurgaon, rent office udyog vihar, udyog vihar phase 1 real estate',
                'content_file' => 'best-property-agent-in-udyog-vihar-phase-1.html',
            ],
            [
                'slug' => 'best-property-agent-in-udyog-vihar-phase-2',
                'title' => 'Best Property Agent in Udyog Vihar Phase 2: Leasing & Investment in Udyog Vihar',
                'excerpt' => 'The best property agent in Udyog Vihar Phase 2 helps businesses secure offices, warehouses, and commercial units with transparent CAM, lease terms, and location advice.',
                'meta_title' => 'Best Property Agent in Udyog Vihar Phase 2 | Gurgaon',
                'meta_description' => 'Hire the best property agent in Udyog Vihar Phase 2 for commercial leasing and property transactions. Expert negotiation and verified Udyog Vihar listings.',
                'meta_keywords' => 'udyog vihar phase 2 property agent, office lease phase 2 udyog vihar, commercial rent gurgaon, udyog vihar real estate consultant',
                'content_file' => 'best-property-agent-in-udyog-vihar-phase-2.html',
            ],
            [
                'slug' => 'best-property-agent-in-udyog-vihar-phase-3',
                'title' => 'Best Property Agent in Udyog Vihar Phase 3: Commercial Property Specialists',
                'excerpt' => 'Need the best property agent in Udyog Vihar Phase 3? Specialists in office floors, built-to-suit options, and resale commercial assets across Phase 3 blocks.',
                'meta_title' => 'Best Property Agent in Udyog Vihar Phase 3 | Gurgaon',
                'meta_description' => 'Best property agent in Udyog Vihar Phase 3 for office space, commercial buy/sell, and tenant representation. Local pricing and fast shortlists.',
                'meta_keywords' => 'udyog vihar phase 3 property agent, commercial property phase 3, rent office udyog vihar 3, gurgaon office space agent',
                'content_file' => 'best-property-agent-in-udyog-vihar-phase-3.html',
            ],
            [
                'slug' => 'best-property-agent-in-udyog-vihar-phase-4',
                'title' => 'Best Property Agent in Udyog Vihar Phase 4: Office Leasing & Commercial Deals',
                'excerpt' => 'Connect with the best property agent in Udyog Vihar Phase 4 for Grade A and mid-segment offices, CAM clarity, and lease negotiations near Delhi border connectivity.',
                'meta_title' => 'Best Property Agent in Udyog Vihar Phase 4 | Gurgaon',
                'meta_description' => 'Find the best property agent in Udyog Vihar Phase 4 for commercial leasing and investments. Verified offices and expert transaction support.',
                'meta_keywords' => 'udyog vihar phase 4 property agent, office space phase 4, commercial lease udyog vihar, property dealer udyog vihar 4',
                'content_file' => 'best-property-agent-in-udyog-vihar-phase-4.html',
            ],
            [
                'slug' => 'best-property-agent-in-udyog-vihar-phase-5',
                'title' => 'Best Property Agent in Udyog Vihar Phase 5: Modern Commercial & Office Space',
                'excerpt' => 'Looking for the best property agent in Udyog Vihar Phase 5? Help with newer commercial stock, flexible offices, and connectivity-focused workspace shortlists.',
                'meta_title' => 'Best Property Agent in Udyog Vihar Phase 5 | Gurgaon',
                'meta_description' => 'Best property agent in Udyog Vihar Phase 5 for office rent, commercial purchase, and sell. Phase 5 market knowledge and end-to-end support.',
                'meta_keywords' => 'udyog vihar phase 5 property agent, office rent phase 5, commercial property udyog vihar 5, gurgaon commercial real estate',
                'content_file' => 'best-property-agent-in-udyog-vihar-phase-5.html',
            ],
            [
                'slug' => 'best-property-agent-in-cyber-city',
                'title' => 'Best Property Agent in Cyber City: Office, Retail & Corporate Housing',
                'excerpt' => 'The best property agent in Cyber City guides corporates on office leasing, retail units, and residential options near DLF Cyber City and major IT parks.',
                'meta_title' => 'Best Property Agent in Cyber City Gurgaon | DLF Cyber City',
                'meta_description' => 'Best property agent in Cyber City for office lease, commercial investment, and nearby residential rent. DLF Cyber City expertise and verified listings.',
                'meta_keywords' => 'cyber city property agent, dlf cyber city office rent, commercial property cyber city, gurgaon cyber city real estate, rent near cyber city',
                'content_file' => 'best-property-agent-in-cyber-city.html',
            ],
            [
                'slug' => 'best-property-agent-in-cyber-hub',
                'title' => 'Best Property Agent in Cyber Hub: Property Near Gurgaon\'s Dining & Business Hub',
                'excerpt' => 'Find the best property agent in Cyber Hub for offices and homes near Gurgaon\'s flagship F&B district—ideal for firms wanting brand-visible locations and walkable amenities.',
                'meta_title' => 'Best Property Agent in Cyber Hub | Gurgaon',
                'meta_description' => 'Best property agent in Cyber Hub for commercial and residential property near DLF Cyber Hub. Leasing, buy/sell, and premium location advice.',
                'meta_keywords' => 'cyber hub property agent, dlf cyber hub gurgaon, office near cyber hub, rent apartment cyber hub area, commercial lease cyber hub',
                'content_file' => 'best-property-agent-in-cyber-hub.html',
            ],
            [
                'slug' => 'best-property-agent-in-mg-road',
                'title' => 'Best Property Agent on MG Road Gurgaon: Central Corridor Properties',
                'excerpt' => 'The best property agent on MG Road helps you buy, sell, or rent along Gurgaon\'s central spine—from retail frontage to apartments and offices near Metro and markets.',
                'meta_title' => 'Best Property Agent on MG Road Gurgaon | Buy & Rent',
                'meta_description' => 'Best property agent on MG Road Gurgaon for residential and commercial property. Central location expertise, verified listings, fair pricing.',
                'meta_keywords' => 'mg road gurgaon property agent, rent mg road gurgaon, buy property mg road, commercial shop mg road, property dealer mg road gurgaon',
                'content_file' => 'best-property-agent-in-mg-road.html',
            ],
            [
                'slug' => 'best-property-agent-in-golf-course-road',
                'title' => 'Best Property Agent on Golf Course Road: Luxury Homes & Premium Offices',
                'excerpt' => 'Hire the best property agent on Golf Course Road for luxury apartments, high-value resale, corporate leases, and investment-grade commercial along Gurgaon\'s flagship boulevard.',
                'meta_title' => 'Best Property Agent Golf Course Road Gurgaon | Luxury',
                'meta_description' => 'Best property agent on Golf Course Road for luxury buy, sell, rent, and commercial space. Sector 42–54 expertise and confidential high-value deals.',
                'meta_keywords' => 'golf course road property agent, luxury apartment golf course road, rent golf course road gurgaon, commercial golf course road, sector 42 43 property',
                'content_file' => 'best-property-agent-in-golf-course-road.html',
            ],
            [
                'slug' => 'best-property-agent-in-golf-course-extension-road',
                'title' => 'Best Property Agent on Golf Course Extension Road: New Gurgaon Premium Corridor',
                'excerpt' => 'The best property agent on Golf Course Extension Road covers premium towers, new launches, and resale in one of Gurgaon\'s fastest-growing luxury and commercial corridors.',
                'meta_title' => 'Best Property Agent Golf Course Extension Road | Gurgaon',
                'meta_description' => 'Best property agent on Golf Course Extension Road for luxury homes, offices, and investment property. Verified listings and corridor-wide market knowledge.',
                'meta_keywords' => 'golf course extension road property agent, gcr gurgaon property, rent golf course extension, buy apartment extension road, new gurgaon luxury property',
                'content_file' => 'best-property-agent-in-golf-course-extension-road.html',
            ],
        ];
    }

    public function run(): void
    {
        $reference = BlogPost::where('slug', 'best-property-agent-in-gurgaon')->first();

        foreach ($this->localities() as $config) {
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
