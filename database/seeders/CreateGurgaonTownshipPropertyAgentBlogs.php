<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class CreateGurgaonTownshipPropertyAgentBlogs extends Seeder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    protected function localities(): array
    {
        return [
            [
                'slug' => 'best-property-agent-in-sohna-road',
                'title' => 'Best Property Agent on Sohna Road: Buy, Sell & Rent in South Gurgaon',
                'excerpt' => 'Find the best property agent on Sohna Road for apartments, floors, and new projects along Gurgaon\'s major south corridor with strong rental and end-user demand.',
                'meta_title' => 'Best Property Agent on Sohna Road | Gurgaon',
                'meta_description' => 'Best property agent on Sohna Road for buying, selling, and renting. Sector 47–49 expertise, verified listings, and transparent deals.',
                'meta_keywords' => 'sohna road property agent, rent sohna road gurgaon, buy flat sohna road, sector 47 48 49 property, sohna road real estate',
                'content_file' => 'best-property-agent-in-sohna-road.html',
            ],
            [
                'slug' => 'best-property-agent-in-southern-peripheral-road',
                'title' => 'Best Property Agent on Southern Peripheral Road (SPR): Gurgaon SPR Corridor',
                'excerpt' => 'The best property agent on Southern Peripheral Road helps buyers and tenants navigate SPR townships, new launches, and resale across south Gurgaon\'s growth belt.',
                'meta_title' => 'Best Property Agent on SPR Gurgaon | Southern Peripheral Road',
                'meta_description' => 'Best property agent on Southern Peripheral Road (SPR) for homes and investments. Local SPR market knowledge and verified Gurgaon listings.',
                'meta_keywords' => 'spr gurgaon property agent, southern peripheral road property, rent spr gurgaon, buy apartment spr corridor, spr real estate gurgaon',
                'content_file' => 'best-property-agent-in-southern-peripheral-road.html',
            ],
            [
                'slug' => 'best-property-agent-in-dwarka-expressway',
                'title' => 'Best Property Agent on Dwarka Expressway: New Gurgaon & NCR Connectivity',
                'excerpt' => 'Need the best property agent on Dwarka Expressway? Expert guidance for projects along the expressway with Delhi connectivity, new societies, and investment potential.',
                'meta_title' => 'Best Property Agent Dwarka Expressway | Gurgaon',
                'meta_description' => 'Best property agent on Dwarka Expressway for buy, sell, and rent. Expressway corridor expertise, RERA-aware advice, and verified listings.',
                'meta_keywords' => 'dwarka expressway property agent, dew expressway gurgaon, rent dwarka expressway, new gurgaon expressway property, sector 102 113 property',
                'content_file' => 'best-property-agent-in-dwarka-expressway.html',
            ],
            [
                'slug' => 'best-property-agent-in-nirvana-country',
                'title' => 'Best Property Agent in Nirvana Country: Premium Township in South Gurgaon',
                'excerpt' => 'Connect with the best property agent in Nirvana Country for society apartments, villas, and rentals in this established premium Gurgaon township.',
                'meta_title' => 'Best Property Agent in Nirvana Country | Gurgaon',
                'meta_description' => 'Best property agent in Nirvana Country for purchases, sales, and leases. Township-wise pricing and smooth society transfers.',
                'meta_keywords' => 'nirvana country property agent, rent nirvana country, buy apartment nirvana country gurgaon, nirvana country real estate',
                'content_file' => 'best-property-agent-in-nirvana-country.html',
            ],
            [
                'slug' => 'best-property-agent-in-malibu-town',
                'title' => 'Best Property Agent in Malibu Town: Homes in Sector 47 Gurgaon',
                'excerpt' => 'The best property agent in Malibu Town guides families and investors through this popular Sector 47 address with strong amenities and resale depth.',
                'meta_title' => 'Best Property Agent in Malibu Town | Gurgaon',
                'meta_description' => 'Best property agent in Malibu Town for buy, sell, and rent in Sector 47. Verified listings and local Malibu Town market expertise.',
                'meta_keywords' => 'malibu town property agent, malibu town gurgaon rent, sector 47 malibu town, buy flat malibu town, malibu town real estate',
                'content_file' => 'best-property-agent-in-malibu-town.html',
            ],
            [
                'slug' => 'best-property-agent-in-mayfield-garden',
                'title' => 'Best Property Agent in Mayfield Garden: Residential Deals in Gurgaon',
                'excerpt' => 'Find the best property agent in Mayfield Garden for builder floors, society flats, and corporate rentals in this well-known Gurgaon neighbourhood.',
                'meta_title' => 'Best Property Agent in Mayfield Garden | Gurgaon',
                'meta_description' => 'Hire the best property agent in Mayfield Garden for buying, selling, and renting. Accurate pricing and end-to-end transaction support.',
                'meta_keywords' => 'mayfield garden property agent, rent mayfield garden gurgaon, buy property mayfield garden, mayfield garden real estate consultant',
                'content_file' => 'best-property-agent-in-mayfield-garden.html',
            ],
            [
                'slug' => 'best-property-agent-in-rosewood-city',
                'title' => 'Best Property Agent in Rosewood City: Township Living in Gurgaon',
                'excerpt' => 'Looking for the best property agent in Rosewood City? Specialists in Rosewood societies, rental demand, and family homes across this Gurgaon township.',
                'meta_title' => 'Best Property Agent in Rosewood City | Gurgaon',
                'meta_description' => 'Best property agent in Rosewood City for apartments, resale, and leases. Society-level expertise and verified Gurgaon inventory.',
                'meta_keywords' => 'rosewood city property agent, rosewood city gurgaon rent, buy apartment rosewood city, rosewood city real estate',
                'content_file' => 'best-property-agent-in-rosewood-city.html',
            ],
            [
                'slug' => 'best-property-agent-in-greenwood-city',
                'title' => 'Best Property Agent in Greenwood City: Buy, Sell & Rent in Gurgaon',
                'excerpt' => 'The best property agent in Greenwood City helps you navigate this established Gurgaon address with diverse housing stock and steady tenant demand.',
                'meta_title' => 'Best Property Agent in Greenwood City | Gurgaon',
                'meta_description' => 'Best property agent in Greenwood City for residential buy, sell, and rent. Local society knowledge and transparent deals.',
                'meta_keywords' => 'greenwood city property agent, greenwood city gurgaon, rent greenwood city, buy flat greenwood city, greenwood city real estate',
                'content_file' => 'best-property-agent-in-greenwood-city.html',
            ],
            [
                'slug' => 'best-property-agent-in-ardee-city',
                'title' => 'Best Property Agent in Ardee City: Property Services in Gurgaon',
                'excerpt' => 'Need the best property agent in Ardee City? Expert help for Ardee City apartments, floors, and rentals with society-wise comparables and fast shortlists.',
                'meta_title' => 'Best Property Agent in Ardee City | Gurgaon',
                'meta_description' => 'Connect with the best property agent in Ardee City for purchases, sales, and leases. Verified listings in Ardee City Gurgaon.',
                'meta_keywords' => 'ardee city property agent, ardee city gurgaon rent, buy property ardee city, ardee city real estate consultant',
                'content_file' => 'best-property-agent-in-ardee-city.html',
            ],
            [
                'slug' => 'best-property-agent-in-sun-city',
                'title' => 'Best Property Agent in Sun City: Homes in Sector 54 & Adjoining Sectors',
                'excerpt' => 'Find the best property agent in Sun City for family apartments, premium rentals, and resale near Golf Course Road and central south Gurgaon corridors.',
                'meta_title' => 'Best Property Agent in Sun City | Gurgaon',
                'meta_description' => 'Best property agent in Sun City for buy, sell, and rent. Sun City township expertise and professional negotiation support.',
                'meta_keywords' => 'sun city property agent, sun city gurgaon rent, sector 54 sun city, buy apartment sun city, sun city real estate gurgaon',
                'content_file' => 'best-property-agent-in-sun-city.html',
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
