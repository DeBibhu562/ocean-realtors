<?php

namespace App\Support;

class GurgaonExtendedLocalityBlogBuilder
{
    /**
     * @return list<int>
     */
    public static function sectorNumbers(): array
    {
        return [59, 60, 61, 62, 63, 64, 66, 68, 69, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function definitions(): array
    {
        $defs = [];

        foreach (self::sectorNumbers() as $sector) {
            $profile = self::sectorProfiles()[$sector];
            $defs[] = [
                'slug' => 'sector-'.$sector.'-gurgaon-property-guide',
                'title' => "Sector {$sector} Gurgaon Property Guide: Prices, Societies & Living",
                'category' => 'sector_guide',
                'sector' => $sector,
                'excerpt' => "Sector {$sector} Gurgaon property guide — {$profile['belt']}. Pricing, societies, and buyer checklist for 2026.",
                'meta_title' => "Sector {$sector} Gurgaon Property Guide 2026",
                'meta_description' => "Sector {$sector} Gurgaon property guide — buy, rent, and invest with local market insights.",
                'meta_keywords' => "sector {$sector} gurgaon, sector {$sector} property, gurgaon sector {$sector}",
            ];
        }

        foreach (self::localityDefinitions() as $locality) {
            $profile = self::localityProfiles()[$locality['key']];
            $defs[] = [
                'slug' => $locality['slug'],
                'title' => $locality['title'],
                'category' => 'locality_guide',
                'locality' => $locality['label'],
                'excerpt' => "{$locality['label']} property guide — {$profile['belt']}. Pricing, societies, and buyer checklist for 2026.",
                'meta_title' => "{$locality['label']} Property Guide 2026",
                'meta_description' => "{$locality['label']} property guide — buy, rent, and invest with local market insights.",
                'meta_keywords' => $locality['keywords'],
            ];
        }

        return $defs;
    }

    /**
     * @return list<array{key: string, slug: string, title: string, label: string, keywords: string}>
     */
    private static function localityDefinitions(): array
    {
        return [
            ['key' => 'sushant_lok_1', 'slug' => 'sushant-lok-phase-1-property-guide', 'title' => 'Sushant Lok Phase 1 Property Guide: Prices, Societies & Living', 'label' => 'Sushant Lok Phase 1 Gurgaon', 'keywords' => 'sushant lok phase 1, sushant lok 1 gurgaon, sushant lok property guide'],
            ['key' => 'sushant_lok_2', 'slug' => 'sushant-lok-phase-2-property-guide', 'title' => 'Sushant Lok Phase 2 Property Guide: Prices, Societies & Living', 'label' => 'Sushant Lok Phase 2 Gurgaon', 'keywords' => 'sushant lok phase 2, sushant lok 2 gurgaon, sector 57 sushant lok'],
            ['key' => 'sushant_lok_3', 'slug' => 'sushant-lok-phase-3-property-guide', 'title' => 'Sushant Lok Phase 3 Property Guide: Prices, Societies & Living', 'label' => 'Sushant Lok Phase 3 Gurgaon', 'keywords' => 'sushant lok phase 3, sushant lok 3 gurgaon, sushant lok property'],
            ['key' => 'south_city_1', 'slug' => 'south-city-1-property-guide', 'title' => 'South City 1 Property Guide: Prices, Societies & Living', 'label' => 'South City 1 Gurgaon', 'keywords' => 'south city 1 gurgaon, south city 1 property, south city 1 flats'],
            ['key' => 'south_city_2', 'slug' => 'south-city-2-property-guide', 'title' => 'South City 2 Property Guide: Prices, Societies & Living', 'label' => 'South City 2 Gurgaon', 'keywords' => 'south city 2 gurgaon, south city 2 property, south city 2 rent'],
            ['key' => 'palam_vihar', 'slug' => 'palam-vihar-property-guide', 'title' => 'Palam Vihar Property Guide: Prices, Societies & Living', 'label' => 'Palam Vihar Gurgaon', 'keywords' => 'palam vihar gurgaon, palam vihar property, palam vihar flats'],
            ['key' => 'nirvana_country', 'slug' => 'nirvana-country-property-guide', 'title' => 'Nirvana Country Property Guide: Prices, Societies & Living', 'label' => 'Nirvana Country Gurgaon', 'keywords' => 'nirvana country gurgaon, nirvana country property, nirvana country villas'],
            ['key' => 'rosewood_city', 'slug' => 'rosewood-city-property-guide', 'title' => 'Rosewood City Property Guide: Prices, Societies & Living', 'label' => 'Rosewood City Gurgaon', 'keywords' => 'rosewood city gurgaon, rosewood city property, rosewood city flats'],
            ['key' => 'malibu_towne', 'slug' => 'malibu-towne-property-guide', 'title' => 'Malibu Towne Property Guide: Prices, Societies & Living', 'label' => 'Malibu Towne Gurgaon', 'keywords' => 'malibu towne gurgaon, malibu towne property, malibu towne flats'],
            ['key' => 'ardee_city', 'slug' => 'ardee-city-property-guide', 'title' => 'Ardee City Property Guide: Prices, Societies & Living', 'label' => 'Ardee City Gurgaon', 'keywords' => 'ardee city gurgaon, ardee city property, ardee city sector 52'],
        ];
    }

    public static function build(array $definition): string
    {
        $html = $definition['category'] === 'sector_guide'
            ? self::sectorGuide((int) $definition['sector'])
            : self::localityGuide($definition['locality'], self::localityProfiles()[self::localityKey($definition['slug'])]);

        $context = [
            'title' => $definition['title'],
            'slug' => $definition['slug'],
        ];

        if ($definition['category'] === 'sector_guide') {
            $context['sector'] = $definition['sector'];
        } else {
            $context['locality'] = $definition['locality'];
        }

        return GurgaonBlogContentBuilder::enrichPublic($html, $definition['category'], $context);
    }

    private static function localityKey(string $slug): string
    {
        return match ($slug) {
            'sushant-lok-phase-1-property-guide' => 'sushant_lok_1',
            'sushant-lok-phase-2-property-guide' => 'sushant_lok_2',
            'sushant-lok-phase-3-property-guide' => 'sushant_lok_3',
            'south-city-1-property-guide' => 'south_city_1',
            'south-city-2-property-guide' => 'south_city_2',
            'palam-vihar-property-guide' => 'palam_vihar',
            'nirvana-country-property-guide' => 'nirvana_country',
            'rosewood-city-property-guide' => 'rosewood_city',
            'malibu-towne-property-guide' => 'malibu_towne',
            'ardee-city-property-guide' => 'ardee_city',
            default => 'palam_vihar',
        };
    }

    /**
     * @return array<int, array{belt: string, highlights: string, buyers: string, connectivity: string}>
     */
    private static function sectorProfiles(): array
    {
        return [
            59 => ['belt' => 'Golf Course Extension — southern residential belt', 'highlights' => 'Established societies, family end-user depth, extension corridor resale activity', 'buyers' => 'Families seeking Golf Course Extension access at mid-premium tickets', 'connectivity' => 'Golf Course Extension Road and Sohna Road south links'],
            60 => ['belt' => 'Sohna Road — southern Gurgaon belt', 'highlights' => 'Highway-linked growth, township and society mix, spacious floor plans', 'buyers' => 'Space-focused families and long-hold end-users', 'connectivity' => 'Sohna Road primary artery toward southern Gurgaon'],
            61 => ['belt' => 'New Gurgaon — northern growth sector', 'highlights' => 'Branded projects, competitive per-sq-ft vs central Gurgaon, verify delivery', 'buyers' => 'First-time buyers and infrastructure-linked investors', 'connectivity' => 'NH-48 and internal New Gurgaon road network'],
            62 => ['belt' => 'Southern Peripheral Road influence zone', 'highlights' => 'SPR-linked commute potential, active new supply, compare developer track records', 'buyers' => 'Value buyers betting on SPR corridor maturity', 'connectivity' => 'Southern Peripheral Road and Golf Course Extension crossover'],
            63 => ['belt' => 'New Gurgaon — SPR northern belt', 'highlights' => 'Township launches and delivered phases, mixed occupancy levels', 'buyers' => 'End-users and investors with 5–7 year horizon', 'connectivity' => 'SPR access and NH-48 connectivity improving'],
            64 => ['belt' => 'New Gurgaon — western growth pocket', 'highlights' => 'Emerging residential supply, infrastructure-dependent appreciation', 'buyers' => 'Forward-looking buyers comparing New Gurgaon corridors', 'connectivity' => 'Western New Gurgaon roads and expressway influence'],
            66 => ['belt' => 'New Gurgaon — mid-northern sector cluster', 'highlights' => 'Branded township phases, club amenities, possession variance by developer', 'buyers' => 'Families wanting modern amenities below central tickets', 'connectivity' => 'NH-48 and SPR internal sector links'],
            68 => ['belt' => 'New Gurgaon — developing residential belt', 'highlights' => 'Newer societies, price discovery still evolving, check RERA status', 'buyers' => 'Patient end-users and growth-oriented investors', 'connectivity' => 'SPR and Sohna Road spillover influence'],
            69 => ['belt' => 'New Gurgaon — southern sector cluster', 'highlights' => 'Township density, family-oriented layouts, growing retail pipeline', 'buyers' => 'First-time buyers and rental investors', 'connectivity' => 'Southern New Gurgaon road network toward Sohna Road'],
            71 => ['belt' => 'SPR — southern residential belt', 'highlights' => 'Highway-adjacent townships, competitive 2–3 BHK pricing', 'buyers' => 'Budget-conscious families seeking gated societies', 'connectivity' => 'Southern Peripheral Road primary commute route'],
            72 => ['belt' => 'SPR corridor — active township belt', 'highlights' => 'Strong marketing visibility, compare delivered vs under-construction quality', 'buyers' => 'End-users prioritising space and amenities', 'connectivity' => 'SPR and Golf Course Extension access'],
            73 => ['belt' => 'SPR — mid-corridor growth sector', 'highlights' => 'Mixed ready and UC inventory, rental depth rising in occupied phases', 'buyers' => 'Investors and families upgrading from Old Gurgaon', 'connectivity' => 'Southern Peripheral Road and internal sector roads'],
            74 => ['belt' => 'SPR — high-activity township hub', 'highlights' => 'One of New Gurgaon\'s most traded sectors, diverse developer mix', 'buyers' => 'Broad buyer pool from end-users to investors', 'connectivity' => 'Strong SPR positioning with improving social infrastructure'],
            75 => ['belt' => 'SPR — established New Gurgaon sector', 'highlights' => 'Delivered phases with visible occupancy, active broker market', 'buyers' => 'End-users wanting ready or near-ready inventory', 'connectivity' => 'SPR corridor with Golf Course Extension links'],
            76 => ['belt' => 'SPR — western sector cluster', 'highlights' => 'Township competition on pricing, verify maintenance and club charges', 'buyers' => 'Value seekers comparing SPR societies side by side', 'connectivity' => 'SPR and Dwarka Expressway influence zone'],
            77 => ['belt' => 'Dwarka Expressway — northern belt', 'highlights' => 'Expressway-linked growth, branded launches, infrastructure momentum', 'buyers' => 'Buyers betting on western Gurgaon connectivity', 'connectivity' => 'Dwarka Expressway and NH-48 access'],
            78 => ['belt' => 'Dwarka Expressway — central corridor sector', 'highlights' => 'High-profile township projects, strong end-user marketing', 'buyers' => 'Families and NRIs targeting expressway corridor', 'connectivity' => 'Dwarka Expressway primary artery to Delhi'],
            79 => ['belt' => 'Dwarka Expressway — mid-corridor belt', 'highlights' => 'Mixed possession timelines, compare developer delivery history', 'buyers' => 'Investors and end-users with flexible move-in dates', 'connectivity' => 'Expressway access and internal sector widening'],
            80 => ['belt' => 'Dwarka Expressway — western residential belt', 'highlights' => 'Competitive per-sq-ft, emerging occupancy in delivered phases', 'buyers' => 'First-time buyers and long-hold investors', 'connectivity' => 'Dwarka Expressway and western Gurgaon road links'],
            81 => ['belt' => 'Dwarka Expressway — frontier western sector', 'highlights' => 'Newest supply pipeline, verify utility readiness and access roads', 'buyers' => 'Growth-tolerant buyers with 7+ year horizon', 'connectivity' => 'Expressway western stretch and NH-48 crossover'],
        ];
    }

    /**
     * @return array<string, array{belt: string, highlights: string, buyers: string, connectivity: string}>
     */
    private static function localityProfiles(): array
    {
        return [
            'sushant_lok_1' => ['belt' => 'Central Gurgaon — Sushant Lok Phase 1 premium belt', 'highlights' => 'Established low-rise character, Golf Course Road adjacency, strong corporate rental depth', 'buyers' => 'Executives and families wanting central prestige', 'connectivity' => 'Golf Course Road, MG Road, and Cyber City within practical commutes'],
            'sushant_lok_2' => ['belt' => 'Sushant Lok Phase 2 — Sector 57 corridor', 'highlights' => 'Active resale market, mix of builder floors and societies, steady end-user demand', 'buyers' => 'Families upgrading from smaller apartments', 'connectivity' => 'Golf Course Road and Huda City Centre metro influence'],
            'sushant_lok_3' => ['belt' => 'Sushant Lok Phase 3 — southern extension', 'highlights' => 'Relative value within Sushant Lok brand, growing family buyer interest', 'buyers' => 'Buyers wanting Sushant Lok address at moderate tickets', 'connectivity' => 'Golf Course Extension and Sohna Road access'],
            'south_city_1' => ['belt' => 'South City 1 — flagship Gurgaon township', 'highlights' => 'Gated community, club amenities, high rental and resale liquidity', 'buyers' => 'Families prioritising township security and schools nearby', 'connectivity' => 'Golf Course Extension and central Gurgaon road network'],
            'south_city_2' => ['belt' => 'South City 2 — established family township', 'highlights' => 'Mature occupancy, corporate tenant pool, consistent maintenance standards', 'buyers' => 'End-users and rental investors targeting township living', 'connectivity' => 'Golf Course Extension and Nirvana Country adjacency'],
            'palam_vihar' => ['belt' => 'Palam Vihar — Old Gurgaon value belt', 'highlights' => 'Accessible builder floor tickets, strong local market, verify title block-by-block', 'buyers' => 'Budget-conscious families and first-time buyers', 'connectivity' => 'Palam Vihar road network and Bijwasan metro influence'],
            'nirvana_country' => ['belt' => 'Nirvana Country — premium gated township', 'highlights' => 'Large villas and spacious apartments, club lifestyle, top family address tag', 'buyers' => 'Upgraders seeking space, privacy, and township prestige', 'connectivity' => 'Golf Course Extension with strong internal township roads'],
            'rosewood_city' => ['belt' => 'Rosewood City — Sohna Road township', 'highlights' => 'Planned township living, competitive pricing vs central addresses, family amenities', 'buyers' => 'Families wanting gated community on Sohna Road corridor', 'connectivity' => 'Sohna Road and Golf Course Extension crossover'],
            'malibu_towne' => ['belt' => 'Malibu Towne — Golf Course Extension township', 'highlights' => 'Gated society options, mid-premium pricing, active end-user market', 'buyers' => 'Families near Golf Course Extension schools and retail', 'connectivity' => 'Golf Course Extension Road primary artery'],
            'ardee_city' => ['belt' => 'Ardee City — Sector 52 planned township', 'highlights' => 'Established township with mixed inventory ages, steady rental demand', 'buyers' => 'End-users comparing central vs extension corridor townships', 'connectivity' => 'Golf Course Extension and central Gurgaon links'],
        ];
    }

    /**
     * @param  array{belt: string, highlights: string, buyers: string, connectivity: string}  $profile
     */
    private static function sectorGuide(int $sector): string
    {
        $p = self::sectorProfiles()[$sector];

        return self::guideBody("Sector {$sector} Gurgaon", $p, 'sector');
    }

    /**
     * @param  array{belt: string, highlights: string, buyers: string, connectivity: string}  $profile
     */
    private static function localityGuide(string $label, array $profile): string
    {
        return self::guideBody($label, $profile, 'locality');
    }

    /**
     * @param  array{belt: string, highlights: string, buyers: string, connectivity: string}  $profile
     */
    private static function guideBody(string $label, array $profile, string $type): string
    {
        $short = $type === 'sector' ? $label : $label;

        return <<<HTML
<p><strong>{$label}</strong> sits in the {$profile['belt']} — an actively traded address on Gurgaon's residential map. This 2026 property guide covers connectivity, typical inventory, pricing drivers, and buyer due diligence.</p>
<p>Whether you buy, rent, or invest, {$short} decisions should be society-specific — not based on area averages alone.</p>

<h2>Location and Connectivity</h2>
<p>{$label} connects via internal roads to Gurgaon's arterial network. {$profile['connectivity']}. Peak-hour commute to Cyber City, Golf Course Road, and Udyog Vihar varies by exact society — test from your shortlisted society gate, not the area centre alone.</p>
<p>Metro last-mile may require a short drive or cab depending on pocket location; factor daily transit cost if you rely on public transport.</p>

<h2>Market Character</h2>
<p>{$profile['highlights']}. Typical buyers: {$profile['buyers']}. Rental depth depends on society quality, parking norms, and proximity to employment hubs — furnished 2–3 BHK often leads corporate leasing.</p>

<h2>Property Types</h2>
<ul>
<li>Apartments in gated societies and townships — common in planned developments</li>
<li>Independent builder floors — popular for space and terrace access</li>
<li>Resale ready inventory — inspection certainty versus renovation needs</li>
<li>Under-construction phases on growth corridors — verify RERA and delivery timelines</li>
</ul>

<h2>Pricing and Due Diligence</h2>
<ul>
<li>Compare carpet area and all-in cost including society transfer and parking</li>
<li>Review title chain and encumbrance basics before token</li>
<li>Inspect power backup, water pressure, and noise from approach roads</li>
<li>Request recent registration references in the same society or lane</li>
<li>Check society NOC requirements, club charges, and outstanding dues</li>
</ul>

<h2>Who Should Consider {$label}?</h2>
<p>Buyers who fit {$profile['buyers']} and accept this area's commute profile often find strong value when they compare three societies minimum and negotiate with transaction evidence.</p>

<h2>Conclusion</h2>
<p>{$label} rewards prepared buyers who verify connectivity on the ground and use registration comps — not portal asking rates alone. Ocean Realtors provides curated shortlists and paperwork support for this market.</p>
HTML;
    }
}
