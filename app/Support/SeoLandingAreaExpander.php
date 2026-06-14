<?php

namespace App\Support;

class SeoLandingAreaExpander
{
    private const IMAGES = [
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80',
    ];

    public static function slugFromArea(string $area): string
    {
        $slug = strtolower($area);
        $slug = preg_replace('/\bpart\s+/i', 'part-', $slug);

        return trim((string) preg_replace('/[^a-z0-9]+/', '-', $slug), '-');
    }

    /**
     * @return array<string, mixed>
     */
    public static function expand(string $area): array
    {
        $slugKey = self::slugFromArea($area);
        $profile = self::profileFor($area);
        $copy = self::copyFor($area, $profile);
        $imageIndex = crc32($slugKey) % count(self::IMAGES);

        return [
            'area' => $area,
            'slug_key' => $slugKey,
            'hero_image' => self::IMAGES[$imageIndex],
            'location_detail' => $copy['location_detail'],
            'hero_subtitle' => $copy['hero_subtitle'],
            'why_intro' => $copy['why_intro'],
            'guide_lead' => $copy['guide_lead'],
            'guide_body' => $copy['guide_body'],
            'highlights' => $copy['highlights'],
        ];
    }

    /**
     * @param  list<string>  $areas
     * @return list<array<string, mixed>>
     */
    public static function expandAll(array $areas): array
    {
        $definitions = [];

        foreach ($areas as $area) {
            if (! is_string($area) || trim($area) === '') {
                continue;
            }

            $definitions[] = self::expand(trim($area));
        }

        return $definitions;
    }

    private static function profileFor(string $area): string
    {
        $lower = strtolower($area);

        if (preg_match('/^sector\s+(\d+)/i', $area, $matches)) {
            $number = (int) $matches[1];

            if ($number <= 17) {
                return 'sector_old';
            }

            if ($number <= 57) {
                return 'sector_mid';
            }

            return 'sector_new';
        }

        return match (true) {
            str_contains($lower, 'manesar') => 'industrial',
            in_array($lower, ['sohna', 'pataudi', 'farrukhnagar'], true) => 'town',
            default => 'locality',
        };
    }

    /**
     * @return array{location_detail: string, hero_subtitle: string, why_intro: string, guide_lead: string, guide_body: string, highlights: list<string>}
     */
    private static function copyFor(string $area, string $profile): array
    {
        return match ($profile) {
            'sector_old' => [
                'location_detail' => "{$area}, Gurgaon (Old Gurgaon)",
                'hero_subtitle' => "Ocean Realtors lists 3 BHK builder floors in {$area} — Old Gurgaon convenience, practical connectivity, and off-market homes priced with local transaction data.",
                'why_intro' => "{$area} is an established Old Gurgaon address with mature streets, active local markets, and steady demand for independent 3 BHK builder floors at accessible price points.",
                'guide_lead' => "{$area} suits buyers who want a recognisable Gurgaon sector address with plotted-home privacy and straightforward civic amenities.",
                'guide_body' => "We benchmark {$area} 3 BHK pricing to recent Old Gurgaon registrations and share owner-direct floors that rarely appear on mass listing portals.",
                'highlights' => [
                    'Established Old Gurgaon sector address',
                    'Value-oriented 3 BHK builder floor stock',
                    'Strong end-user and rental demand',
                    'Off-market inventory through Ocean Realtors',
                ],
            ],
            'sector_mid' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy 3 BHK builder floors in {$area} with Ocean Realtors — central Gurgaon access, verified papers, and intelligence-led shortlists.",
                'why_intro' => "{$area} offers a balanced Gurgaon location with improving connectivity, stable end-user demand, and a healthy mix of renovated and newly built 3 BHK builder floors.",
                'guide_lead' => "{$area} works for families who want independent 3 BHK living with practical access to retail, schools, and arterial roads.",
                'guide_body' => "Ocean Realtors tracks {$area} lane comps and introduces off-market 3 BHK options priced against recent sales — not inflated portal asking rates.",
                'highlights' => [
                    "Established {$area} residential market",
                    'Mix of renovated and new 3 BHK plates',
                    'Consistent rental and end-user interest',
                    'Dedicated agent and paperwork review',
                ],
            ],
            'sector_new' => [
                'location_detail' => "{$area}, New Gurgaon",
                'hero_subtitle' => "Explore 3 BHK builder floors for sale in {$area} — New Gurgaon growth corridor, modern infrastructure, and Ocean Realtors off-market deals.",
                'why_intro' => "{$area} sits in Gurgaon's expanding development belt with new road links, township adjacency, and rising demand for spacious 3 BHK builder floors on independent plots.",
                'guide_lead' => "{$area} appeals to buyers positioning in New Gurgaon who want low-rise 3 BHK layouts, parking, and upgrade-friendly floor plates.",
                'guide_body' => "Our research desk benchmarks {$area} pricing to corridor transactions and curates owner-direct 3 BHK listings before they hit public portals.",
                'highlights' => [
                    'New Gurgaon growth and connectivity',
                    'Newer 3 BHK builder floor construction quality',
                    'End-user and investor crossover demand',
                    'Title diligence and market-linked pricing',
                ],
            ],
            'industrial' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Ocean Realtors curates 3 BHK builder floors near {$area} — industrial corridor growth, practical pricing, and verified papers.",
                'why_intro' => "{$area} anchors a major Gurgaon employment and logistics belt, supporting rental demand and end-user interest in nearby plotted 3 BHK builder floors.",
                'guide_lead' => "{$area}-area 3 BHK floors suit buyers who want space and value near Gurgaon's industrial and commercial expansion zones.",
                'guide_body' => "We compare {$area}-adjacent 3 BHK stock to corridor comps and share off-market homes with transparent transfer terms.",
                'highlights' => [
                    'Strong employment corridor adjacency',
                    'Rental depth from workforce housing demand',
                    'Value-led 3 BHK builder floor options nearby',
                    'Local pricing intelligence and paper checks',
                ],
            ],
            'town' => [
                'location_detail' => "{$area}, Gurgaon district",
                'hero_subtitle' => "Find 3 BHK builder floors for sale near {$area} with Ocean Realtors — emerging township growth, spacious plots, and off-market owner introductions.",
                'why_intro' => "{$area} is an established Gurgaon-region town with improving road connectivity, local commerce, and growing interest in independent 3 BHK builder floors.",
                'guide_lead' => "{$area} suits buyers exploring plotted homes beyond core Gurgaon with better space value and long-term growth potential.",
                'guide_body' => "Ocean Realtors tracks {$area}-area pricing and introduces owner-direct 3 BHK floors priced with local transaction evidence.",
                'highlights' => [
                    'Growing Gurgaon-region town address',
                    'Spacious 3 BHK builder floor stock',
                    'Improving connectivity to Gurgaon cores',
                    'Off-market access and paperwork support',
                ],
            ],
            default => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Ocean Realtors offers 3 BHK builder floors in {$area} — local Gurgaon pocket, practical connectivity, and intelligence-led pricing.",
                'why_intro' => "{$area} is a recognised Gurgaon locality with active residential demand, local amenities, and steady supply of 3 BHK builder floors on independent plots.",
                'guide_lead' => "{$area} works for buyers who want a specific neighbourhood identity with low-rise 3 BHK privacy and parking.",
                'guide_body' => "We benchmark {$area} 3 BHK pricing to neighbouring lane sales and share off-market options where sellers accept data-backed offers.",
                'highlights' => [
                    "Established {$area} residential pocket",
                    'Independent 3 BHK builder floor availability',
                    'End-user and rental demand',
                    'Verified papers before recommendations',
                ],
            ],
        };
    }
}
