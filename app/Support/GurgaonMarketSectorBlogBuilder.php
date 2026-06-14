<?php

namespace App\Support;

class GurgaonMarketSectorBlogBuilder
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function definitions(): array
    {
        $sectors = [82, 83, 84, 85, 86, 89, 90, 91, 92, 95, 102, 104, 106, 108, 109, 111, 113, 114, 115];
        $defs = [
            self::def('gurgaon-real-estate-market-report-2026', 'Gurgaon Real Estate Market Report 2026: Prices, Demand & Outlook', 'market_report', 'Gurgaon real estate market report 2026 — premium vs New Gurgaon trends, rental demand, supply pipeline, and buyer outlook.', 'Gurgaon Real Estate Market Report 2026', 'Gurgaon property market report 2026 — prices, trends, and expert outlook.', 'gurgaon real estate market report 2026, gurgaon property market'),
            self::def('property-price-trends-in-gurgaon', 'Property Price Trends in Gurgaon: 2026 Per-Sq-Ft Analysis', 'price_trends', 'Property price trends in Gurgaon — DLF, Golf Course Road, mid sectors, and New Gurgaon rate movement in 2026.', 'Property Price Trends Gurgaon 2026', 'Gurgaon property price trends — per sq ft analysis by corridor.', 'property price trends gurgaon, gurgaon price increase 2026'),
            self::def('fastest-growing-areas-in-gurgaon', 'Fastest Growing Areas in Gurgaon: Corridors to Watch in 2026', 'fastest_growing', 'Fastest growing areas in Gurgaon — Dwarka Expressway, SPR, Sohna Road, and New Gurgaon sectors with infrastructure momentum.', 'Fastest Growing Areas Gurgaon 2026', 'Fastest growing localities in Gurgaon — investment and buyer guide.', 'fastest growing areas gurgaon, gurgaon growth corridors'),
            self::def('upcoming-infrastructure-projects-gurgaon', 'Upcoming Infrastructure Projects in Gurgaon: 2026 Roadmap', 'infrastructure', 'Upcoming infrastructure projects in Gurgaon — expressways, metro extensions, and civic upgrades impacting property values.', 'Upcoming Infrastructure Projects Gurgaon', 'Gurgaon infrastructure projects 2026 — roads, metro, and impact.', 'upcoming infrastructure gurgaon, gurgaon development projects'),
            self::def('impact-of-metro-expansion-gurgaon', 'Impact of Metro Expansion on Gurgaon Property: 2026 Analysis', 'metro_impact', 'Impact of metro expansion on Gurgaon property — station-linked premiums, corridors benefiting, and buyer implications.', 'Metro Expansion Impact Gurgaon Property', 'How metro expansion affects Gurgaon property prices and rents.', 'metro expansion gurgaon, gurgaon metro property impact'),
            self::def('luxury-housing-demand-trends-gurgaon', 'Luxury Housing Demand Trends in Gurgaon: 2026 Premium Market', 'luxury_trends', 'Luxury housing demand trends in Gurgaon — Golf Course Road, DLF City, and branded tower absorption in 2026.', 'Luxury Housing Demand Gurgaon 2026', 'Luxury home demand trends in Gurgaon — premium market outlook.', 'luxury housing demand gurgaon, premium property trends gurgaon'),
            self::def('affordable-housing-trends-gurgaon', 'Affordable Housing Trends in Gurgaon: Value Segments 2026', 'affordable_trends', 'Affordable housing trends in Gurgaon — Old Gurgaon, Palam Vihar, and New Gurgaon entry-level pricing and demand.', 'Affordable Housing Trends Gurgaon 2026', 'Affordable housing market trends in Gurgaon — budget buyer guide.', 'affordable housing trends gurgaon, budget flats gurgaon 2026'),
            self::def('nri-investment-trends-gurgaon', 'NRI Investment Trends in Gurgaon: 2026 Overseas Buyer Report', 'nri_trends', 'NRI investment trends in Gurgaon — overseas buyer preferences, premium corridors, and remote purchase patterns in 2026.', 'NRI Investment Trends Gurgaon 2026', 'NRI property investment trends in Gurgaon — 2026 market report.', 'nri investment trends gurgaon, nri property gurgaon 2026'),
            self::def('commercial-market-trends-gurgaon', 'Commercial Market Trends in Gurgaon: Office & Retail 2026', 'commercial_trends', 'Commercial market trends in Gurgaon — office absorption, retail footfall, and warehouse demand in 2026.', 'Commercial Market Trends Gurgaon 2026', 'Gurgaon commercial real estate trends — office and retail outlook.', 'commercial market trends gurgaon, office market gurgaon 2026'),
            self::def('future-of-gurgaon-real-estate', 'Future of Gurgaon Real Estate: 2026–2030 Outlook', 'future_estate', 'Future of Gurgaon real estate — long-term corridor evolution, New Gurgaon maturity, and premium market resilience.', 'Future of Gurgaon Real Estate', 'Future outlook for Gurgaon property market — 2026 to 2030.', 'future gurgaon real estate, gurgaon property outlook'),
        ];

        foreach ($sectors as $sector) {
            $slug = 'sector-'.$sector.'-gurgaon-property-guide';
            $defs[] = [
                'slug' => $slug,
                'title' => "Sector {$sector} Gurgaon Property Guide: Prices, Societies & Living",
                'category' => 'sector_guide',
                'sector' => $sector,
                'excerpt' => "Sector {$sector} Gurgaon property guide — New Gurgaon living, societies, pricing factors, and buyer checklist for 2026.",
                'meta_title' => "Sector {$sector} Gurgaon Property Guide 2026",
                'meta_description' => "Sector {$sector} Gurgaon property guide — buy, rent, and invest with local market insights.",
                'meta_keywords' => "sector {$sector} gurgaon, sector {$sector} property, new gurgaon sector {$sector}",
            ];
        }

        $defs[] = [
            'slug' => 'sector-37d-gurgaon-property-guide',
            'title' => 'Sector 37D Gurgaon Property Guide: Prices, Societies & Living',
            'category' => 'sector_guide',
            'sector' => '37D',
            'excerpt' => 'Sector 37D Gurgaon property guide — Dwarka Expressway belt, township projects, pricing, and buyer due diligence.',
            'meta_title' => 'Sector 37D Gurgaon Property Guide 2026',
            'meta_description' => 'Sector 37D Gurgaon property guide — societies, pricing, and investment notes.',
            'meta_keywords' => 'sector 37d gurgaon, sector 37d property, dwarka expressway sector 37d',
        ];

        return $defs;
    }

    /**
     * @return array<string, mixed>
     */
    private static function def(
        string $slug,
        string $title,
        string $category,
        string $excerpt,
        string $metaTitle,
        string $metaDescription,
        string $metaKeywords,
    ): array {
        return [
            'slug' => $slug,
            'title' => $title,
            'category' => $category,
            'excerpt' => $excerpt,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $metaKeywords,
        ];
    }

    public static function build(array $definition): string
    {
        $html = $definition['category'] === 'sector_guide'
            ? self::sectorGuide($definition['sector'])
            : self::marketBody($definition['category']);

        $context = ['title' => $definition['title'], 'slug' => $definition['slug']];
        if ($definition['category'] === 'sector_guide') {
            $context['sector'] = $definition['sector'];
        }

        return GurgaonBlogContentBuilder::enrichPublic($html, $definition['category'], $context);
    }

    private static function marketBody(string $category): string
    {
        $focus = match ($category) {
            'market_report' => ['keyword' => 'Gurgaon real estate market report 2026', 'intro' => 'Gurgaon\'s 2026 property market operates at three speeds: supply-constrained premium central belts, steady mid-sector end-user churn, and high-supply New Gurgaon corridors where infrastructure and developer delivery determine winners.', 'sections' => self::reportSections()],
            'price_trends' => ['keyword' => 'property price trends in Gurgaon', 'intro' => 'Price trends in 2026 reflect lane-level reality more than city averages — Golf Course Road and DLF phases hold premium bands while Dwarka Expressway and SPR townships compete on per-sq-ft value and possession clarity.', 'sections' => self::priceSections()],
            'fastest_growing' => ['keyword' => 'fastest growing areas in Gurgaon', 'intro' => 'Fastest growing areas combine visible infrastructure progress, township occupancy, and employment spillover — not marketing claims alone.', 'sections' => self::growthSections()],
            'infrastructure' => ['keyword' => 'upcoming infrastructure projects in Gurgaon', 'intro' => 'Upcoming projects span expressway completion, arterial widening, metro extensions, and civic upgrades — each affects corridors differently.', 'sections' => self::infraSections()],
            'metro_impact' => ['keyword' => 'impact of metro expansion on Gurgaon property', 'intro' => 'Metro expansion reshapes commute calculus — societies with genuine last-mile access to stations often see rental premium and resale depth versus equidistant but poorly connected blocks.', 'sections' => self::metroSections()],
            'luxury_trends' => ['keyword' => 'luxury housing demand trends in Gurgaon', 'intro' => 'Luxury demand in 2026 favours branded towers, large formats, and Golf Course Road / DLF addresses with corporate and expatriate tenant depth.', 'sections' => self::luxurySections()],
            'affordable_trends' => ['keyword' => 'affordable housing trends in Gurgaon', 'intro' => 'Affordable segment activity concentrates in Old Gurgaon sectors, Palam Vihar, and southern New Gurgaon townships where ticket sizes stay accessible to first-time buyers.', 'sections' => self::affordableSections()],
            'nri_trends' => ['keyword' => 'NRI investment trends in Gurgaon', 'intro' => 'NRIs continue targeting premium ready inventory and developer-branded New Gurgaon projects with clear RERA trails — remote diligence and PoA execution remain standard.', 'sections' => self::nriSections()],
            'commercial_trends' => ['keyword' => 'commercial market trends in Gurgaon', 'intro' => 'Commercial trends show stable Cyber City and Golf Course Road office demand, growing retail on high streets, and logistics strength near Manesar.', 'sections' => self::commercialSections()],
            'future_estate' => ['keyword' => 'future of Gurgaon real estate', 'intro' => 'Gurgaon\'s long-term story pairs NCR employment concentration with expanding residential belts — premium addresses likely retain liquidity as New Gurgaon matures over the next decade.', 'sections' => self::futureSections()],
            default => ['keyword' => 'Gurgaon real estate', 'intro' => 'Gurgaon remains a core NCR property market.', 'sections' => []],
        };

        return self::article($focus['keyword'], $focus['intro'], $focus['sections']);
    }

    /**
     * @param  int|string  $sector
     */
    private static function sectorGuide(int|string $sector): string
    {
        $label = is_string($sector) ? "Sector {$sector}" : "Sector {$sector}";
        $profiles = [
            82 => ['belt' => 'New Gurgaon — Dwarka Expressway corridor', 'highlights' => 'Branded townships, highway access, rising end-user occupancy', 'buyers' => 'First-time buyers and long-hold investors'],
            83 => ['belt' => 'New Gurgaon — expressway-adjacent growth belt', 'highlights' => 'Mixed township phases, competitive 2–3 BHK pricing', 'buyers' => 'Families seeking modern amenities'],
            84 => ['belt' => 'New Gurgaon southern expressway belt', 'highlights' => 'Large floor plans, improving retail pipeline', 'buyers' => 'Space-focused end-users'],
            85 => ['belt' => 'New Gurgaon — SPR / expressway crossover influence', 'highlights' => 'Active new supply, verify delivery track records', 'buyers' => 'Value-oriented upgraders'],
            86 => ['belt' => 'New Gurgaon emerging residential belt', 'highlights' => 'Infrastructure-linked growth, varied project quality', 'buyers' => 'Forward-looking buyers'],
            89 => ['belt' => 'New Gurgaon — southern sector cluster', 'highlights' => 'Township density, family-oriented layouts', 'buyers' => 'End-users and rental investors'],
            90 => ['belt' => 'New Gurgaon — highway-linked sector', 'highlights' => 'Commute to Delhi and Manesar belts', 'buyers' => 'Budget-conscious families'],
            91 => ['belt' => 'New Gurgaon growth sector', 'highlights' => 'Newer societies, compare possession timelines', 'buyers' => 'First-time and investor crossover'],
            92 => ['belt' => 'New Gurgaon — expanding southern belt', 'highlights' => 'Emerging social infrastructure', 'buyers' => 'Long-hold end-users'],
            95 => ['belt' => 'New Gurgaon — Sohna Road influence zone', 'highlights' => 'Spacious apartments, southern connectivity', 'buyers' => 'Families priced out of central Gurgaon'],
            102 => ['belt' => 'New Gurgaon — western growth pocket', 'highlights' => 'Township launches, check RERA and delivery', 'buyers' => 'Investors and end-users'],
            104 => ['belt' => 'New Gurgaon — expressway southern belt', 'highlights' => 'Competitive per-sq-ft vs central addresses', 'buyers' => 'Value and space seekers'],
            106 => ['belt' => 'New Gurgaon — developing sector cluster', 'highlights' => 'Infrastructure-dependent appreciation', 'buyers' => 'Patient long-hold buyers'],
            108 => ['belt' => 'New Gurgaon — southern residential belt', 'highlights' => 'Mixed ready and under-construction stock', 'buyers' => 'Compare ready vs UC carefully'],
            109 => ['belt' => 'New Gurgaon — township-heavy sector', 'highlights' => 'Club amenities, maintenance varies by developer', 'buyers' => 'Family end-users'],
            111 => ['belt' => 'New Gurgaon — emerging end-user sector', 'highlights' => 'Growing occupancy in delivered phases', 'buyers' => 'First-time buyers'],
            113 => ['belt' => 'New Gurgaon — southern expansion belt', 'highlights' => 'Price discovery still evolving', 'buyers' => 'Investors with 7+ year horizon'],
            114 => ['belt' => 'New Gurgaon — frontier residential sector', 'highlights' => 'Verify access roads and utility readiness', 'buyers' => 'Risk-tolerant growth buyers'],
            115 => ['belt' => 'New Gurgaon — southernmost active belt', 'highlights' => 'Space value, longer commutes to Cyber City', 'buyers' => 'Space-first families'],
            '37D' => ['belt' => 'Dwarka Expressway — Sector 37D township belt', 'highlights' => 'High-profile township projects, strong marketing visibility', 'buyers' => 'End-users and investors betting on expressway maturity'],
        ];

        $key = is_string($sector) ? $sector : $sector;
        $p = $profiles[$key] ?? ['belt' => 'New Gurgaon residential belt', 'highlights' => 'Diverse township and apartment stock', 'buyers' => 'End-users and investors'];

        return <<<HTML
<p><strong>{$label} Gurgaon</strong> sits in the {$p['belt']} — an actively traded address in Gurgaon's expanding residential map. This 2026 property guide covers connectivity, typical inventory, pricing drivers, and buyer due diligence.</p>
<p>Whether you buy, rent, or invest, {$label} decisions should be society-specific — not based on sector averages alone.</p>

<h2>Location and Connectivity</h2>
<p>{$label} connects via internal sector roads to NH-48, Dwarka Expressway, SPR, or Sohna Road depending on exact pocket. Peak-hour commute to Cyber City and Golf Course Road varies — test from your shortlisted society gate.</p>
<p>Metro last-mile may require drive or cab; factor this into daily cost if you rely on public transit.</p>

<h2>Market Character</h2>
<p>{$p['highlights']}. Typical buyers: {$p['buyers']}. Rental depth grows as occupancy rises in delivered townships — furnished 2–3 BHK often leads leasing.</p>

<h2>Property Types</h2>
<ul>
<li>Apartments in branded townships — dominant product type</li>
<li>Select builder floors on sector approach roads</li>
<li>Ready resale and under-construction phases — compare risk/reward</li>
</ul>

<h2>Pricing and Due Diligence</h2>
<ul>
<li>Compare carpet area and all-in charges (PLC, parking, club)</li>
<li>Review RERA status for under-construction phases</li>
<li>Inspect delivered phases for build quality and occupancy</li>
<li>Check society transfer norms and title before token</li>
</ul>

<h2>Who Should Consider {$label}?</h2>
<p>Buyers who fit {$p['buyers']} and accept New Gurgaon commute profiles often find {$label} delivers space and amenities below central Gurgaon tickets — with homework on developer delivery.</p>

<h2>Conclusion</h2>
<p>{$label} Gurgaon rewards prepared buyers who compare three societies minimum, use registration comps, and verify infrastructure on the ground. Ocean Realtors provides curated shortlists and paperwork support for this sector.</p>
HTML;
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function reportSections(): array
    {
        return [
            ['heading' => '2026 Market Snapshot', 'paragraphs' => ['Premium central inventory remains supply-tight with steady corporate rental demand.', 'New Gurgaon sees competitive launches — differentiation is delivery and occupancy, not brochures alone.'], 'list' => ['Premium: price resilience, lower yields', 'Mid sectors: balanced end-user churn', 'New Gurgaon: value + infrastructure bet']],
            ['heading' => 'Demand Drivers', 'paragraphs' => ['Corporate hiring along Golf Course Road and Cyber City supports premium rents.', 'Family upgraders drive 3–4 BHK demand in townships on Sohna Road and SPR.']],
            ['heading' => 'Supply Watch', 'paragraphs' => ['Under-construction pipeline is heaviest on Dwarka Expressway — monitor possession slippage.', 'Resale ready stock offers inspection certainty versus payment-plan launches.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function priceSections(): array
    {
        return [
            ['heading' => 'Premium Corridor Pricing', 'paragraphs' => ['Golf Course Road and DLF City hold top per-sq-ft bands with low vacancy on quality inventory.', 'Renovation and view premiums widen spreads within the same tower.']],
            ['heading' => 'Mid and New Gurgaon Bands', 'paragraphs' => ['Sectors 56–57 and adjoining belts trade below premium with strong rental depth.', 'New Gurgaon townships compete on all-in ticket size — compare carpet, not super built-up marketing.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function growthSections(): array
    {
        return [
            ['heading' => 'Corridors Leading Growth', 'paragraphs' => ['Dwarka Expressway and SPR show highest infrastructure-linked interest.', 'Sohna Road benefits from southern expansion and larger floor plans.'], 'list' => ['Dwarka Expressway townships', 'SPR sectors 75–95 belt', 'Sohna Road adjacency', 'Select Sector 82–115 pockets']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function infraSections(): array
    {
        return [
            ['heading' => 'Road and Expressway Projects', 'paragraphs' => ['Dwarka Expressway completion improves Delhi–Gurgaon western access.', 'SPR and internal sector widening reduce peak-hour friction in pockets.']],
            ['heading' => 'Metro and Transit', 'paragraphs' => ['Planned extensions and last-mile connectivity proposals — verify official project status vs announcements.', 'Rapid Metro and Yellow Line remain core for existing premium belts.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function metroSections(): array
    {
        return [
            ['heading' => 'Station-Linked Premiums', 'paragraphs' => ['Societies within walkable or 5-minute access often command rent premium.', 'Mislabeled "metro nearby" listings require on-ground last-mile verification.']],
            ['heading' => 'Beneficiary Corridors', 'paragraphs' => ['Sikanderpur, MG Road, and HUDA City Centre belts remain strongest for metro-linked living.', 'Future lines may benefit New Gurgaon if station locations are confirmed.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function luxurySections(): array
    {
        return [
            ['heading' => 'Luxury Demand Drivers', 'paragraphs' => ['Corporate leadership relocations and expatriate leases support furnished premium inventory.', 'Large 4 BHK and branded towers outperform on tenant quality vs yield percentage.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function affordableSections(): array
    {
        return [
            ['heading' => 'Affordable Segment Dynamics', 'paragraphs' => ['Old Gurgaon builder floors and Palam Vihar offer entry tickets below New Gurgaon townships.', 'First-time buyers compare EMI vs rent carefully in 2026 rate environment.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function nriSections(): array
    {
        return [
            ['heading' => 'NRI Buyer Preferences', 'paragraphs' => ['Ready possession and branded developers reduce remote-buy risk.', 'Premium DLF and Golf Course Road remain top NRI targets for capital preservation.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function commercialSections(): array
    {
        return [
            ['heading' => 'Office and Retail Outlook', 'paragraphs' => ['Cyber City and Golf Course Road maintain Grade A office depth.', 'Retail follows residential catchments on high streets and township hubs.']],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function futureSections(): array
    {
        return [
            ['heading' => '2026–2030 Themes', 'paragraphs' => ['New Gurgaon occupancy maturation should improve resale liquidity in delivered townships.', 'Premium central addresses likely retain benchmark status for NCR luxury residential.'], 'list' => ['Infrastructure completion cycles', 'Developer consolidation and delivery track records', 'Hybrid work impact on commute-weighted rents', 'Continued NRI and corporate tenant depth']],
        ];
    }

    /**
     * @param  list<array{heading: string, paragraphs: list<string>, list?: list<string>}>  $sections
     */
    private static function article(string $keyword, string $intro, array $sections): string
    {
        $html = '<p>'.$intro.'</p>';
        $html .= '<p>This report on <strong>'.$keyword.'</strong> helps buyers, investors, and tenants interpret Gurgaon\'s 2026 market with evidence — not hype. Ocean Realtors tracks local transactions and provides curated guidance by phone or WhatsApp.</p>';

        foreach ($sections as $section) {
            $html .= '<h2>'.htmlspecialchars($section['heading'], ENT_QUOTES, 'UTF-8').'</h2>';
            foreach ($section['paragraphs'] as $paragraph) {
                $html .= '<p>'.$paragraph.'</p>';
            }
            if (! empty($section['list'])) {
                $html .= '<ul>';
                foreach ($section['list'] as $item) {
                    $html .= '<li>'.$item.'</li>';
                }
                $html .= '</ul>';
            }
        }

        $html .= '<h2>How Ocean Realtors Helps</h2>';
        $html .= '<p>For data-backed shortlists and transaction support across Gurgaon — including '.$keyword.' — contact Ocean Realtors for verified options and end-to-end guidance.</p>';

        return $html;
    }
}
