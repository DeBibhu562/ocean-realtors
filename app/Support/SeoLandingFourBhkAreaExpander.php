<?php

namespace App\Support;

class SeoLandingFourBhkAreaExpander
{
    private const IMAGES = [
        'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1605276374102-40f008b4b0bc?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1600&q=80',
    ];

    public static function slugFromArea(string $area): string
    {
        return SeoLandingAreaExpander::slugFromArea($area);
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
            'guide_highlight' => $copy['guide_highlight'],
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

        if (preg_match('/^dlf\s+/i', $area) || str_contains($lower, 'dlf alameda') || str_contains($lower, 'dlf gardencity')) {
            return 'dlf';
        }

        if (str_contains($lower, 'udyog vihar')) {
            return 'industrial';
        }

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

        if (str_contains($lower, 'manesar')) {
            return 'industrial';
        }

        if (in_array($lower, ['sohna', 'pataudi', 'farrukhnagar'], true)) {
            return 'town';
        }

        if (str_contains($lower, 'dwarka expressway')
            || str_contains($lower, 'southern peripheral')
            || str_contains($lower, 'golf course extension')
            || str_contains($lower, 'sohna road')
            || str_contains($lower, 'ansal esencia')
            || str_contains($lower, 'emerald hills')
            || str_contains($lower, 'heritage city')) {
            return 'corridor';
        }

        if (str_contains($lower, 'sushant lok')
            || str_contains($lower, 'golf course road')
            || str_contains($lower, 'cyber')
            || str_contains($lower, 'mg road')
            || str_contains($lower, 'hamilton court')
            || str_contains($lower, 'richmond park')) {
            return 'premium';
        }

        if (str_contains($lower, 'palam vihar')
            || str_contains($lower, 'ashok vihar')
            || str_contains($lower, 'saraswati')
            || str_contains($lower, 'laxman vihar')
            || str_contains($lower, 'shivpuri')) {
            return 'west';
        }

        if (str_contains($lower, 'south city')
            || str_contains($lower, 'nirvana')
            || str_contains($lower, 'malibu')
            || str_contains($lower, 'mayfield')
            || str_contains($lower, 'rosewood')
            || str_contains($lower, 'ardee')
            || str_contains($lower, 'sun city')
            || str_contains($lower, 'vatika')
            || str_contains($lower, 'central park')
            || str_contains($lower, 'vipul')
            || str_contains($lower, 'orchid island')
            || str_contains($lower, 'greenwood')) {
            return 'township';
        }

        return 'old_gurgaon';
    }

    /**
     * @return array{location_detail: string, hero_subtitle: string, why_intro: string, guide_lead: string, guide_body: string, guide_highlight: string, highlights: list<string>}
     */
    private static function copyFor(string $area, string $profile): array
    {
        return match ($profile) {
            'dlf' => [
                'location_detail' => "{$area}, DLF City, Gurgaon",
                'hero_subtitle' => "Ocean Realtors curates 4 BHK builder floors in {$area} — DLF City's large-format low-rise homes with off-market access, society diligence, and pricing tied to real registrations.",
                'why_intro' => "{$area} attracts buyers who need four bedrooms, generous living space, and DLF address prestige on independent plots. Spacious floors here trade discreetly; public portals skew toward smaller 3 BHK stock and outdated premium quotes.",
                'guide_lead' => "A 4 BHK builder floor in {$area} suits joint families and senior professionals who want servant accommodation, terrace decks, and multi-car parking without moving into a high-rise.",
                'guide_body' => "Our desk benchmarks {$area} 4 BHK ask-rates against recent lane registrations and surfaces owner-direct floors where pricing leaves room versus stale broker quotes.",
                'guide_highlight' => "A 4 BHK builder floor for sale in {$area} balances DLF liquidity with low-rise privacy — evaluate bedroom spread, terrace rights, and renovation scope alongside per-sq-ft value.",
                'highlights' => [
                    'Large-format 4 BHK plates on DLF City plots',
                    'Servant quarter, terrace, and parking assessment',
                    'Society NOC and title chain screening',
                    'Off-market spacious floors through owner networks',
                ],
            ],
            'premium' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy 4 BHK builder floors for sale in {$area} with Ocean Realtors — premium Gurgaon corridor homes, verified papers, and off-market spacious-floor intelligence.",
                'why_intro' => "{$area} draws end-users upgrading from apartments who need four bedrooms, home-office space, and entertaining areas on independent plots. Large builder floors here trade on layout quality and neighbour profile more than portal visibility.",
                'guide_lead' => "4 BHK builder floors in {$area} suit buyers planning multi-generational living or executive relocations in one of Gurgaon's most searched residential corridors.",
                'guide_body' => "We compare {$area} 4 BHK rates to corridor registrations and shortlist homes with transparent transfer terms — including spacious floors that never reach mass-market portals.",
                'guide_highlight' => "Residential 4 BHK builder floors in {$area} offer room to grow — weigh terrace usability, servant accommodation, and parking count alongside headline price.",
                'highlights' => [
                    'Premium corridor 4 BHK pricing benchmarks',
                    'Floor plate, terrace, and parking review',
                    'Title and society transfer diligence',
                    'Off-market spacious homes via owner networks',
                ],
            ],
            'township' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Explore 4 BHK builder floors for sale near {$area} — township-adjacent spacious homes, society-context checks, and Ocean Realtors market-linked pricing.",
                'why_intro' => "{$area} lifts demand for oversized builder floors on adjoining streets — families want township convenience with four-bedroom privacy and room for guests, staff, and study areas.",
                'guide_lead' => "Large 4 BHK builder floors near {$area} work for buyers who prefer a branded neighbourhood ecosystem while still owning an independent low-rise home.",
                'guide_body' => "Ocean Realtors maps {$area}-area 4 BHK comps, layout quality, and off-market floors where sellers accept data-backed offers.",
                'guide_highlight' => "4 BHK builder floors for sale near {$area} blend township security perception with spacious low-rise living — check access roads, parking, and transfer norms carefully.",
                'highlights' => [
                    'Township-adjacent 4 BHK floor mapping',
                    'Bedroom spread and terrace comparison',
                    'Society / authority transfer screening',
                    'Off-market spacious-floor introductions',
                ],
            ],
            'corridor' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Find 4 BHK builder floors for sale in {$area} — growth-corridor spacious homes with improving connectivity, title checks, and Ocean Realtors off-market shortlists.",
                'why_intro' => "{$area} sits on Gurgaon's expansion spine where new roads pull demand for large independent floors. Early buyers weigh four-bedroom space, build quality, and infrastructure momentum against current per-sq-ft rates.",
                'guide_lead' => "4 BHK builder floors in {$area} appeal to families positioning ahead of corridor maturity who want room for extended households and home offices.",
                'guide_body' => "Our research desk tracks {$area} 4 BHK transactions and flags floors with clean titles and realistic possession timelines — not inflated portal placeholders.",
                'guide_highlight' => "A 4 BHK builder floor for sale in {$area} is often a forward-looking family purchase — verify construction quality, parking, and developer activity nearby before committing.",
                'highlights' => [
                    'Growth-corridor 4 BHK transaction tracking',
                    'Construction quality and layout assessment',
                    'Price per sq ft vs recent sales',
                    'Off-market spacious-floor parcels',
                ],
            ],
            'industrial' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Ocean Realtors lists 4 BHK builder floors near {$area} — industrial-belt adjacency, practical spacious-home pricing, and verified title papers.",
                'why_intro' => "{$area} anchors enterprise activity that supports demand for large builder floors nearby — whether for senior staff housing, joint families, or long-term holds on approach roads.",
                'guide_lead' => "4 BHK builder floors near {$area} suit buyers who want four-bedroom space and connectivity to Gurgaon's employment hubs at practical price points.",
                'guide_body' => "We screen {$area}-area 4 BHK stock for layout quality, access, and ownership clarity before coordinating site inspections.",
                'guide_highlight' => "Spacious builder floors near {$area} require attention to access and build quality — Ocean Realtors clarifies transfer path and realistic handover timelines on every shortlist.",
                'highlights' => [
                    'Access-road and layout verification',
                    'Industrial-belt 4 BHK pricing comps',
                    'Title chain screening',
                    'Off-market spacious-floor sourcing',
                ],
            ],
            'west' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy 4 BHK builder floors for sale in {$area} — west Gurgaon spacious homes at accessible price points, with Ocean Realtors title diligence and off-market deals.",
                'why_intro' => "{$area} offers large builder floors for buyers who need four bedrooms and independent living without ultra-premium DLF or Golf Course tags.",
                'guide_lead' => "4 BHK builder floors in {$area} work for value-conscious joint families planning spacious low-rise living with parking and terrace access.",
                'guide_body' => "We benchmark {$area} 4 BHK rates to neighbouring lane sales and share owner-direct floors priced with registration evidence.",
                'guide_highlight' => "Spacious 4 BHK builder floors in {$area} often deliver better sq-ft value — weigh metro access, parking, and title clarity alongside headline price.",
                'highlights' => [
                    'Value-oriented west Gurgaon 4 BHK stock',
                    'Floor plate and parking checks',
                    'Title and dues screening',
                    'Off-market owner introductions',
                ],
            ],
            'sector_old' => [
                'location_detail' => "{$area}, Gurgaon (Old Gurgaon)",
                'hero_subtitle' => "Ocean Realtors helps you buy 4 BHK builder floors in {$area} — Old Gurgaon spacious homes, practical connectivity, and intelligence-led pricing per sq ft.",
                'why_intro' => "{$area} plotted stock includes oversized builder floors where four-bedroom layouts, servant quarters, and terrace decks matter more than marketing gloss. Many trade off-market.",
                'guide_lead' => "Buying a 4 BHK builder floor in {$area} means checking floor spread, parking bays, society norms, and whether the home fits a joint-family budget.",
                'guide_body' => "Our team compares {$area} 4 BHK ask-rates to recent Old Gurgaon registrations and introduces discreet owner sales before they hit broad portals.",
                'guide_highlight' => "A 4 BHK builder floor for sale in {$area} can offer strong space value in central Gurgaon — verify encumbrance, terrace rights, and transfer charges early.",
                'highlights' => [
                    'Old Gurgaon sector 4 BHK comps',
                    'Layout, parking, and terrace site checks',
                    'MC / society transfer diligence',
                    'Off-market spacious-floor network',
                ],
            ],
            'sector_mid' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy 4 BHK builder floors for sale in {$area} with Ocean Realtors — mid Gurgaon spacious homes, verified title papers, and off-market floor intelligence.",
                'why_intro' => "{$area} offers large builder floors for buyers who want a sector-branded address with four bedrooms, parking, and room to customise — layout quality often matters more than portal asking rates.",
                'guide_lead' => "4 BHK builder floors in {$area} suit families comparing bedroom spread, servant accommodation, and terrace size before committing.",
                'guide_body' => "Ocean Realtors benchmarks {$area} 4 BHK price per sq ft against recent sales and shares owner-direct floors not broadly advertised online.",
                'guide_highlight' => "A 4 BHK builder floor for sale in {$area} balances Gurgaon connectivity with spacious low-rise privacy — diligence title and parking before token.",
                'highlights' => [
                    'Mid Gurgaon sector 4 BHK pricing comps',
                    'Floor plate and terrace assessment',
                    'Society / authority transfer screening',
                    'Off-market spacious-floor introductions',
                ],
            ],
            'sector_new' => [
                'location_detail' => "{$area}, New Gurgaon",
                'hero_subtitle' => "Explore 4 BHK builder floors for sale in {$area} — New Gurgaon growth-sector spacious homes, modern connectivity, and Ocean Realtors off-market shortlists.",
                'why_intro' => "{$area} sits in Gurgaon's expanding belt where new roads and townships drive demand for large independent floors. Buyers weigh four-bedroom space and build quality against current per-sq-ft rates.",
                'guide_lead' => "4 BHK builder floors in {$area} appeal to families entering the New Gurgaon corridor who want room for guests, staff, and home offices on independent plots.",
                'guide_body' => "Our desk tracks {$area} 4 BHK registrations and curates floors with clean titles and realistic possession timelines — not inflated portal placeholders.",
                'guide_highlight' => "Spacious 4 BHK builder floors in {$area} are often a forward-looking family purchase — verify construction quality, parking count, and developer activity nearby.",
                'highlights' => [
                    'New Gurgaon sector 4 BHK transaction data',
                    'Construction quality and layout review',
                    'Price per sq ft vs corridor sales',
                    'Off-market New Gurgaon spacious floors',
                ],
            ],
            'town' => [
                'location_detail' => "{$area}, Gurgaon district",
                'hero_subtitle' => "Find 4 BHK builder floors for sale near {$area} with Ocean Realtors — emerging township-region spacious homes, large plots, and verified title papers.",
                'why_intro' => "{$area} is a growing Gurgaon-region address with improving connectivity and rising interest in oversized independent builder floors for joint families and custom spacious living.",
                'guide_lead' => "4 BHK builder floors near {$area} suit buyers who want four-bedroom space and plot value beyond core Gurgaon premiums.",
                'guide_body' => "Ocean Realtors tracks {$area}-area 4 BHK pricing and introduces owner-direct floors with registration-backed quotes.",
                'guide_highlight' => "4 BHK builder floors for sale near {$area} can offer better sq-ft value — weigh road links, layout quality, and title clarity carefully.",
                'highlights' => [
                    'Gurgaon-region town 4 BHK market tracking',
                    'Spacious floor options for joint families',
                    'Improving connectivity to Gurgaon cores',
                    'Title diligence and off-market access',
                ],
            ],
            default => [
                'location_detail' => "{$area}, Gurgaon (Old Gurgaon)",
                'hero_subtitle' => "Find 4 BHK builder floors for sale in {$area} with Ocean Realtors — established Gurgaon locality spacious homes, verified papers, and off-market floor access.",
                'why_intro' => "{$area} has active local demand for large builder floors where owners upgrade, subdivide plots, or exit spacious holdings privately rather than advertising openly.",
                'guide_lead' => "4 BHK builder floors in {$area} suit buyers who want a recognisable neighbourhood with room for extended families and low-rise privacy.",
                'guide_body' => "Ocean Realtors tracks {$area} 4 BHK pricing and shares owner-direct floors with transparent transfer terms.",
                'guide_highlight' => "Spacious 4 BHK builder floors for sale in {$area} reward buyers who diligence title and layout — not just headline price per sq ft.",
                'highlights' => [
                    "Established {$area} 4 BHK market knowledge",
                    'Bedroom spread and terrace comparisons',
                    'Title screening before visits',
                    'Off-market spacious-floor deals',
                ],
            ],
        };
    }
}
