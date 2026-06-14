<?php

namespace App\Support;

class SeoLandingPlotAreaExpander
{
    private const IMAGES = [
        'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1582407947304-fd86fe028fbc?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600585152915-d208bec867a1?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1600607687644-c7171b42498f?auto=format&fit=crop&w=1600&q=80',
        'https://images.unsplash.com/photo-1605276374102-40f008b4b0bc?auto=format&fit=crop&w=1600&q=80',
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
                'hero_subtitle' => "Ocean Realtors sources residential plots in {$area} — DLF City land parcels with title diligence, off-market access, and pricing tied to real registrations.",
                'why_intro' => "{$area} land commands premium hold-value in DLF City. Parcels change hands discreetly; public portals rarely show accurate plot inventory. We screen frontage, depth, and transfer path before recommending a site visit.",
                'guide_lead' => "A plot purchase in {$area} means evaluating buildable area, road width, and whether the title supports construction or long-term holding in DLF City's mature fabric.",
                'guide_body' => "Our land desk benchmarks {$area} ask-rates per sq yd against recent registry entries and surfaces owner-direct parcels where pricing leaves room versus stale broker quotes.",
                'guide_highlight' => "A plot for sale in {$area} is a capital-allocation decision — buyers balance DLF address prestige, corner premiums, and construction flexibility against long-term Gurgaon liquidity.",
                'highlights' => [
                    'DLF City title chain and encumbrance screening',
                    'Plot dimensions, frontage, and corner-premium notes',
                    'Society / MC transfer and CLU path explained',
                    'Off-market land through Ocean Realtors networks',
                ],
            ],
            'premium' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy plots for sale in {$area} with Ocean Realtors — premium Gurgaon corridor land, verified papers, and off-market parcel intelligence.",
                'why_intro' => "{$area} attracts end-users and investors who want a premium address before they build. Land here trades on frontage, neighbour profile, and title clarity more than flashy online listings suggest.",
                'guide_lead' => "Plots in {$area} suit buyers planning a custom home or a long hold in one of Gurgaon's most searched residential corridors.",
                'guide_body' => "We compare {$area} land rates to corridor registrations and shortlist parcels with transparent transfer terms — including deals that never reach mass-market portals.",
                'guide_highlight' => "Residential land in {$area} offers design freedom and address prestige — evaluate road access, plot depth, and authority approvals alongside price per sq yd.",
                'highlights' => [
                    'Premium corridor land pricing benchmarks',
                    'Corner and road-width assessment on shortlist',
                    'Title and transfer diligence before token',
                    'Off-market plots via owner networks',
                ],
            ],
            'township' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Explore plots for sale near {$area} — township-adjacent land parcels, society-context checks, and Ocean Realtors market-linked pricing.",
                'why_intro' => "{$area} lifts demand for plotted land on adjoining streets — buyers want township convenience with the flexibility to build an independent home on their own schedule.",
                'guide_lead' => "Land near {$area} works for families who prefer a branded neighbourhood ecosystem while still owning the plot outright.",
                'guide_body' => "Ocean Realtors maps {$area}-area parcel comps, society adjacency, and off-market land where sellers accept data-backed offers.",
                'guide_highlight' => "Plots for sale near {$area} blend township security perception with the freedom of custom construction — check access roads and transfer norms carefully.",
                'highlights' => [
                    'Township-adjacent parcel mapping',
                    'Plot size and facing comparison across blocks',
                    'Society / authority transfer screening',
                    'Off-market land introductions',
                ],
            ],
            'corridor' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Find plots for sale in {$area} — growth-corridor land with improving connectivity, title checks, and Ocean Realtors off-market shortlists.",
                'why_intro' => "{$area} sits on Gurgaon's expansion spine where new roads and townships pull plotted land demand. Early buyers weigh infrastructure momentum against current price per sq yd.",
                'guide_lead' => "Land in {$area} appeals to build-to-live and invest-to-build buyers positioning ahead of corridor maturity.",
                'guide_body' => "Our research desk tracks {$area} land transactions and flags parcels with clean titles and realistic possession timelines — not inflated portal placeholders.",
                'guide_highlight' => "A plot for sale in {$area} is often a forward-looking bet — verify CLU status, access width, and developer activity nearby before committing.",
                'highlights' => [
                    'Growth-corridor land transaction tracking',
                    'CLU and authority approval basics reviewed',
                    'Price per sq yd vs recent sales',
                    'Off-market corridor parcels',
                ],
            ],
            'industrial' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Ocean Realtors lists plots near {$area} — industrial-belt adjacency, practical land pricing, and verified title papers.",
                'why_intro' => "{$area} anchors enterprise activity that supports demand for nearby plotted land — whether for staff housing builds or long-term land banking on approach roads.",
                'guide_lead' => "Plots near {$area} suit buyers who want space value and connectivity to Gurgaon's employment hubs.",
                'guide_body' => "We screen {$area}-area parcels for use permissibility, access, and ownership clarity before coordinating site inspections.",
                'guide_highlight' => "Land near {$area} requires extra attention to zoning and access — Ocean Realtors clarifies transfer path and realistic build timelines on every shortlist.",
                'highlights' => [
                    'Zoning and access-road verification',
                    'Industrial-belt land pricing comps',
                    'Title chain screening',
                    'Off-market parcel sourcing',
                ],
            ],
            'west' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy plots for sale in {$area} — west Gurgaon land at accessible price points, with Ocean Realtors title diligence and off-market deals.",
                'why_intro' => "{$area} offers plotted land for buyers who want space and independent construction without ultra-premium DLF or Golf Course tags.",
                'guide_lead' => "Plots in {$area} work for value-conscious families planning a custom home on their own plot.",
                'guide_body' => "We benchmark {$area} land rates to neighbouring lane sales and share owner-direct parcels priced with registration evidence.",
                'guide_highlight' => "Residential plots in {$area} often deliver better sq yd value — weigh metro access, road width, and title clarity alongside headline price.",
                'highlights' => [
                    'Value-oriented west Gurgaon land stock',
                    'Plot dimension and frontage checks',
                    'Title and dues screening',
                    'Off-market owner introductions',
                ],
            ],
            'sector_old' => [
                'location_detail' => "{$area}, Gurgaon (Old Gurgaon)",
                'hero_subtitle' => "Ocean Realtors helps you buy plots in {$area} — Old Gurgaon land parcels, practical connectivity, and intelligence-led pricing per sq yd.",
                'why_intro' => "{$area} plotted land trades in a dense, established market where title clarity and road access matter more than marketing gloss. Many parcels move off-market.",
                'guide_lead' => "Buying a plot in {$area} means checking boundaries, society or MC norms, and whether the parcel fits your build budget.",
                'guide_body' => "Our team compares {$area} land ask-rates to recent Old Gurgaon registrations and introduces discreet owner sales before they hit broad portals.",
                'guide_highlight' => "A plot for sale in {$area} can offer strong space value in central Gurgaon — verify encumbrance, frontage, and transfer charges early.",
                'highlights' => [
                    'Old Gurgaon sector land comps',
                    'Boundary and road-access site checks',
                    'MC / society transfer diligence',
                    'Off-market parcel network',
                ],
            ],
            'sector_mid' => [
                'location_detail' => "{$area}, Gurgaon",
                'hero_subtitle' => "Buy plots for sale in {$area} with Ocean Realtors — mid Gurgaon land parcels, verified title papers, and off-market parcel intelligence.",
                'why_intro' => "{$area} offers plotted land for buyers who want a sector-branded address with room to build — title clarity and road width often matter more than portal asking rates.",
                'guide_lead' => "Land in {$area} suits families comparing plot sizes, facing, and transfer norms before committing to construction.",
                'guide_body' => "Ocean Realtors benchmarks {$area} price per sq yd against recent sales and shares owner-direct parcels not broadly advertised online.",
                'guide_highlight' => "A plot for sale in {$area} balances Gurgaon connectivity with independent build freedom — diligence title and access before token.",
                'highlights' => [
                    'Mid Gurgaon sector land pricing comps',
                    'Plot dimension and frontage assessment',
                    'Society / authority transfer screening',
                    'Off-market land introductions',
                ],
            ],
            'sector_new' => [
                'location_detail' => "{$area}, New Gurgaon",
                'hero_subtitle' => "Explore plots for sale in {$area} — New Gurgaon growth-sector land, modern connectivity, and Ocean Realtors off-market shortlists.",
                'why_intro' => "{$area} sits in Gurgaon's expanding plotted belt where new roads and townships drive land demand. Buyers weigh infrastructure momentum against current price per sq yd.",
                'guide_lead' => "Plots in {$area} appeal to build-to-live and invest-to-build buyers entering the New Gurgaon corridor.",
                'guide_body' => "Our land desk tracks {$area} registrations and curates parcels with clean titles and realistic possession timelines — not inflated portal placeholders.",
                'guide_highlight' => "Residential land in {$area} is often a forward-looking purchase — verify CLU status, access width, and developer activity nearby.",
                'highlights' => [
                    'New Gurgaon sector land transaction data',
                    'CLU and authority approval basics',
                    'Price per sq yd vs corridor sales',
                    'Off-market New Gurgaon parcels',
                ],
            ],
            'town' => [
                'location_detail' => "{$area}, Gurgaon district",
                'hero_subtitle' => "Find plots for sale near {$area} with Ocean Realtors — emerging township-region land, spacious parcels, and verified title papers.",
                'why_intro' => "{$area} is a growing Gurgaon-region address with improving connectivity and rising interest in independent plotted land for custom homes.",
                'guide_lead' => "Land near {$area} suits buyers who want plot value and space beyond core Gurgaon premiums.",
                'guide_body' => "Ocean Realtors tracks {$area}-area land pricing and introduces owner-direct parcels with registration-backed quotes.",
                'guide_highlight' => "Plots for sale near {$area} can offer better sq yd value — weigh road links, title clarity, and build timeline carefully.",
                'highlights' => [
                    'Gurgaon-region town land market tracking',
                    'Spacious plot options for custom builds',
                    'Improving connectivity to Gurgaon cores',
                    'Title diligence and off-market access',
                ],
            ],
            default => [
                'location_detail' => "{$area}, Gurgaon (Old Gurgaon)",
                'hero_subtitle' => "Find plots for sale in {$area} with Ocean Realtors — established Gurgaon locality land, verified papers, and off-market parcel access.",
                'why_intro' => "{$area} has active local demand for plotted land where owners upgrade, subdivide, or exit holdings privately rather than advertising openly.",
                'guide_lead' => "Land in {$area} suits buyers who want a recognisable neighbourhood with room to build a low-rise home.",
                'guide_body' => "Ocean Realtors tracks {$area} parcel pricing and shares owner-direct plots with transparent transfer terms.",
                'guide_highlight' => "Residential plots for sale in {$area} reward buyers who diligence title and access — not just headline price per sq yd.",
                'highlights' => [
                    "Established {$area} land market knowledge",
                    'Plot size and facing comparisons',
                    'Title screening before visits',
                    'Off-market land deals',
                ],
            ],
        };
    }
}
