<?php

namespace App\Support;

class GurgaonApartmentRentalBlogBuilder
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function definitions(): array
    {
        return [
            self::def('2-bhk-flats-in-gurgaon', '2 BHK Flats in Gurgaon: Best Areas, Prices & Buyer Guide 2026', 'apartment_2bhk', '2 BHK flats in Gurgaon — best sectors, price bands, societies, and tips for couples and small families buying or renting in NCR.', '2 BHK Flats in Gurgaon 2026', 'Find 2 BHK flats in Gurgaon — best areas, prices, and society tips.', '2 bhk flats gurgaon, 2 bhk apartment gurgaon, two bhk gurgaon'),
            self::def('3-bhk-flats-in-gurgaon', '3 BHK Flats in Gurgaon: Top Societies & Locality Guide', 'apartment_3bhk', '3 BHK flats in Gurgaon — family-friendly societies, carpet vs built-up pricing, and corridors from DLF City to New Gurgaon.', '3 BHK Flats in Gurgaon 2026', '3 BHK apartments in Gurgaon — best projects, areas, and buyer guide.', '3 bhk flats gurgaon, 3 bhk apartment gurgaon, three bhk gurgaon'),
            self::def('4-bhk-luxury-apartments-in-gurgaon', '4 BHK Luxury Apartments in Gurgaon: Premium Towers & Villas', 'apartment_4bhk', '4 BHK luxury apartments in Gurgaon — Golf Course Road, DLF City, and branded townships for spacious premium living.', '4 BHK Luxury Apartments Gurgaon', '4 BHK luxury flats in Gurgaon — premium societies and buyer checklist.', '4 bhk luxury gurgaon, luxury 4 bhk apartment gurgaon'),
            self::def('studio-apartments-in-gurgaon', 'Studio Apartments in Gurgaon: Compact Living for Professionals', 'apartment_studio', 'Studio apartments in Gurgaon — Cyber City adjacency, furnished rentals, and compact ownership options for young professionals.', 'Studio Apartments in Gurgaon', 'Studio flats in Gurgaon — best areas, rent, and buying tips.', 'studio apartment gurgaon, studio flat gurgaon rent'),
            self::def('gated-communities-in-gurgaon', 'Gated Communities in Gurgaon: Best Townships & Security', 'apartment_gated', 'Gated communities in Gurgaon — South City, Nirvana, DLF townships, and New Gurgaon societies with clubs and 24/7 security.', 'Gated Communities in Gurgaon', 'Best gated communities and townships in Gurgaon for families.', 'gated community gurgaon, gated society gurgaon'),
            self::def('apartments-near-metro-stations-gurgaon', 'Apartments Near Metro Stations in Gurgaon: Commute-Friendly Homes', 'apartment_metro', 'Apartments near metro stations in Gurgaon — Sikanderpur, MG Road, HUDA City Centre corridors and rental-friendly societies.', 'Apartments Near Metro Gurgaon', 'Flats near metro stations in Gurgaon — best localities and commute tips.', 'apartments near metro gurgaon, metro connected flats gurgaon'),
            self::def('smart-homes-in-gurgaon', 'Smart Homes in Gurgaon: Automation, Security & Modern Living', 'apartment_smart', 'Smart homes in Gurgaon — home automation, app-controlled access, and new projects offering connected living in 2026.', 'Smart Homes in Gurgaon 2026', 'Smart homes and automated apartments in Gurgaon — projects and features.', 'smart homes gurgaon, home automation gurgaon apartments'),
            self::def('pet-friendly-apartments-in-gurgaon', 'Pet-Friendly Apartments in Gurgaon: Societies That Welcome Pets', 'apartment_pets', 'Pet-friendly apartments in Gurgaon — societies with pet policies, parks nearby, and tips for tenants and owners with dogs or cats.', 'Pet Friendly Apartments Gurgaon', 'Pet-friendly flats and societies in Gurgaon — policies and best areas.', 'pet friendly apartments gurgaon, pet allowed society gurgaon'),
            self::def('best-family-apartments-in-gurgaon', 'Best Family Apartments in Gurgaon: Schools, Parks & Space', 'apartment_family', 'Best family apartments in Gurgaon — 3–4 BHK societies near schools, with clubs, parks, and stable neighbour profiles.', 'Best Family Apartments Gurgaon', 'Top family-friendly apartments in Gurgaon — societies and localities.', 'family apartments gurgaon, best flats for families gurgaon'),
            self::def('premium-apartments-in-gurgaon', 'Premium Apartments in Gurgaon: Branded Residences & Amenities', 'apartment_premium', 'Premium apartments in Gurgaon — branded towers, concierge services, and Golf Course Road / DLF inventory for upscale buyers.', 'Premium Apartments in Gurgaon', 'Premium and branded apartments in Gurgaon — buyer guide 2026.', 'premium apartments gurgaon, luxury flats gurgaon'),
            self::def('top-builders-in-gurgaon', 'Top Builders in Gurgaon: Reputation, Delivery & Project Quality', 'builder_overview', 'Top builders in Gurgaon — DLF, M3M, Godrej, Sobha, and more compared on delivery track record and buyer satisfaction.', 'Top Builders in Gurgaon 2026', 'Best real estate builders in Gurgaon — reputation and project comparison.', 'top builders gurgaon, best developers gurgaon'),
            self::def('dlf-projects-in-gurgaon', 'DLF Projects in Gurgaon: Phases, Townships & Buyer Guide', 'builder_dlf', 'DLF projects in Gurgaon — DLF City phases, Alameda, Gardencity, and premium towers. What buyers should know in 2026.', 'DLF Projects in Gurgaon', 'DLF residential projects in Gurgaon — phases, pricing, and tips.', 'dlf projects gurgaon, dlf city gurgaon flats'),
            self::def('m3m-projects-in-gurgaon', 'M3M Projects in Gurgaon: Luxury Towers & Investment Outlook', 'builder_m3m', 'M3M projects in Gurgaon — Golf Course Extension, Dwarka Expressway, and premium launches. Society comparison and due diligence.', 'M3M Projects in Gurgaon', 'M3M Gurgaon projects — luxury apartments and buyer guide.', 'm3m projects gurgaon, m3m gurgaon flats'),
            self::def('signature-global-projects-gurgaon', 'Signature Global Projects in Gurgaon: Affordable to Mid-Segment', 'builder_signature', 'Signature Global projects in Gurgaon — New Gurgaon and SPR townships with value pricing and family-oriented layouts.', 'Signature Global Projects Gurgaon', 'Signature Global Gurgaon projects — societies and investment notes.', 'signature global gurgaon, signature global projects'),
            self::def('smartworld-projects-gurgaon', 'Smart World Projects in Gurgaon: Townships & 2026 Launches', 'builder_smartworld', 'Smart World projects in Gurgaon — residential townships, payment plans, and buyer checklist for New Gurgaon corridors.', 'Smart World Projects Gurgaon', 'Smart World Gurgaon projects — flats, townships, and tips.', 'smartworld gurgaon, smart world projects gurgaon'),
            self::def('godrej-properties-gurgaon', 'Godrej Properties in Gurgaon: Projects, Quality & Pricing', 'builder_godrej', 'Godrej Properties in Gurgaon — branded residential projects, amenity standards, and end-user appeal on key corridors.', 'Godrej Properties Gurgaon', 'Godrej Gurgaon projects — apartments and buyer guide.', 'godrej properties gurgaon, godrej flats gurgaon'),
            self::def('sobha-projects-gurgaon', 'Sobha Projects in Gurgaon: Craftsmanship & Premium Living', 'builder_sobha', 'Sobha projects in Gurgaon — construction quality focus, premium specifications, and societies for discerning buyers.', 'Sobha Projects Gurgaon', 'Sobha Gurgaon residential projects — quality, pricing, and tips.', 'sobha projects gurgaon, sobha gurgaon flats'),
            self::def('adani-realty-gurgaon', 'Adani Realty in Gurgaon: Projects & Growth Corridors', 'builder_adani', 'Adani Realty in Gurgaon — residential launches, township scale, and what buyers should verify before booking.', 'Adani Realty Gurgaon Projects', 'Adani Realty Gurgaon — projects, pricing, and buyer checklist.', 'adani realty gurgaon, adani projects gurgaon'),
            self::def('bestech-projects-gurgaon', 'Bestech Projects in Gurgaon: Townships & Apartment Societies', 'builder_bestech', 'Bestech projects in Gurgaon — established townships, rental depth, and mid-premium inventory across Gurgaon belts.', 'Bestech Projects Gurgaon', 'Bestech Gurgaon societies — buy, rent, and locality guide.', 'bestech projects gurgaon, bestech park view gurgaon'),
            self::def('emaar-projects-gurgaon', 'Emaar Projects in Gurgaon: Premium Developments & Amenities', 'builder_emaar', 'Emaar projects in Gurgaon — international developer standards, luxury amenities, and buyer due diligence for premium tickets.', 'Emaar Projects Gurgaon', 'Emaar Gurgaon residential projects — luxury flats and guide.', 'emaar projects gurgaon, emaar gurgaon flats'),
            self::def('best-areas-for-rent-in-gurgaon', 'Best Areas for Rent in Gurgaon: Localities for Every Budget', 'rental_areas', 'Best areas for rent in Gurgaon — DLF phases, Sushant Lok, sectors 56–57, New Gurgaon, and value pockets compared.', 'Best Areas for Rent in Gurgaon 2026', 'Best Gurgaon localities for rent — budgets, commute, and society tips.', 'best areas rent gurgaon, where to rent gurgaon'),
            self::def('furnished-flats-for-rent-gurgaon', 'Furnished Flats for Rent in Gurgaon: Corporate & Expat Guide', 'rental_furnished', 'Furnished flats for rent in Gurgaon — corporate leases, embassy-style requirements, and premium vs budget furnishing.', 'Furnished Flats for Rent Gurgaon', 'Furnished apartments for rent in Gurgaon — areas and pricing.', 'furnished flats rent gurgaon, furnished apartment gurgaon'),
            self::def('rental-market-trends-gurgaon-2026', 'Rental Market Trends in Gurgaon: 2026 Outlook & Pricing', 'rental_trends', 'Rental market trends in Gurgaon 2026 — corporate demand, furnished premiums, and sectors with rising or stable rents.', 'Gurgaon Rental Market Trends 2026', 'Gurgaon rent trends 2026 — prices, demand, and tenant tips.', 'rental market trends gurgaon, gurgaon rent 2026'),
            self::def('pg-vs-apartment-in-gurgaon', 'PG vs Apartment in Gurgaon: Which Is Better for You?', 'rental_pg', 'PG vs apartment in Gurgaon — compare cost, privacy, flexibility, and suitability for students vs working professionals.', 'PG vs Apartment in Gurgaon', 'PG or flat in Gurgaon — cost comparison and decision guide.', 'pg vs flat gurgaon, pg or apartment gurgaon'),
            self::def('rental-yield-comparison-gurgaon', 'Rental Yield Comparison in Gurgaon: Best Corridors Ranked', 'rental_yield', 'Rental yield comparison in Gurgaon — gross vs net returns across DLF, mid sectors, and New Gurgaon for landlords.', 'Rental Yield Comparison Gurgaon', 'Compare rental yields across Gurgaon corridors — investor guide.', 'rental yield comparison gurgaon, best rent return gurgaon'),
            self::def('best-areas-for-working-professionals-gurgaon', 'Best Areas for Working Professionals in Gurgaon: Rent & Buy', 'rental_professionals', 'Best areas for working professionals in Gurgaon — short commutes to Cyber City, Udyog Vihar, and Golf Course Road offices.', 'Best Areas Working Professionals Gurgaon', 'Where to live in Gurgaon as a working professional — rent and buy guide.', 'working professionals gurgaon rent, best area professionals gurgaon'),
            self::def('rent-near-cyber-city-gurgaon', 'Rent Near Cyber City Gurgaon: Best Societies & Commute Tips', 'rental_cyber', 'Rent near Cyber City Gurgaon — DLF phases, Sushant Lok, and sectors with shortest drives to Cyber Hub and DLF Cyber City.', 'Rent Near Cyber City Gurgaon', 'Flats for rent near Cyber City — localities, prices, and tips.', 'rent near cyber city, cyber city gurgaon rent'),
            self::def('rent-near-udyog-vihar-gurgaon', 'Rent Near Udyog Vihar Gurgaon: Affordable & Practical Options', 'rental_udyog', 'Rent near Udyog Vihar Gurgaon — sectors and societies for workforce housing with practical commutes to industrial belts.', 'Rent Near Udyog Vihar Gurgaon', 'Apartments for rent near Udyog Vihar — areas and pricing guide.', 'rent near udyog vihar, udyog vihar gurgaon rent'),
            self::def('rent-near-golf-course-road-gurgaon', 'Rent Near Golf Course Road Gurgaon: Premium Rental Guide', 'rental_golf', 'Rent near Golf Course Road Gurgaon — luxury furnished leases, corporate tenants, and premium society checklist.', 'Rent Near Golf Course Road Gurgaon', 'Premium rentals near Golf Course Road — societies and lease tips.', 'rent golf course road gurgaon, gcr rental flats'),
            self::def('luxury-rental-apartments-gurgaon', 'Luxury Rental Apartments in Gurgaon: Furnished Premium Leases', 'rental_luxury', 'Luxury rental apartments in Gurgaon — Golf Course Road towers, DLF premium inventory, and expatriate-friendly leases.', 'Luxury Rental Apartments Gurgaon', 'Luxury furnished rentals in Gurgaon — top societies and pricing.', 'luxury rental gurgaon, premium rent apartment gurgaon'),
        ];
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
        $html = self::bodyFor($definition['category'], $definition['title'], $definition['slug']);

        return GurgaonBlogContentBuilder::enrichPublic($html, $definition['category'], [
            'title' => $definition['title'],
            'slug' => $definition['slug'],
        ]);
    }

    private static function bodyFor(string $category, string $title, string $slug): string
    {
        $focus = self::focusFor($category, $slug);

        return self::article($focus['keyword'], $focus['intro'], $focus['sections']);
    }

    /**
     * @return array{keyword: string, intro: string, sections: list<array{heading: string, paragraphs: list<string>, list?: list<string>}>}
     */
    private static function focusFor(string $category, string $slug): array
    {
        $base = match ($category) {
            'apartment_2bhk' => ['keyword' => '2 BHK flats in Gurgaon', 'intro' => 'Two-bedroom apartments are Gurgaon\'s highest-volume residential format — ideal for couples, young families, and investors targeting corporate tenants. Inventory spans compact metro-linked units to spacious 2 BHK+study layouts in townships on Sohna Road and Dwarka Expressway.'],
            'apartment_3bhk' => ['keyword' => '3 BHK flats in Gurgaon', 'intro' => 'Three-bedroom flats remain the default choice for Gurgaon families who want society amenities, parking, and room for children or a home office. Demand is strongest in DLF phases, Sushant Lok, South City, and mid sectors 56–57.'],
            'apartment_4bhk' => ['keyword' => '4 BHK luxury apartments in Gurgaon', 'intro' => 'Four-bedroom luxury apartments in Gurgaon offer expansive living-dining spans, servant quarters, and premium club access — concentrated on Golf Course Road, DLF City, and select Golf Course Extension towers.'],
            'apartment_studio' => ['keyword' => 'studio apartments in Gurgaon', 'intro' => 'Studio and 1 RK units suit single professionals prioritising commute over space. Cyber City, MG Road, and Sikanderpur-corridor rentals see the deepest studio demand in Gurgaon.'],
            'apartment_gated' => ['keyword' => 'gated communities in Gurgaon', 'intro' => 'Gated communities combine 24/7 security, internal roads, clubs, and managed landscaping — from South City and Nirvana to DLF townships and New Gurgaon projects.'],
            'apartment_metro' => ['keyword' => 'apartments near metro stations in Gurgaon', 'intro' => 'Metro-linked living reduces NCR commute friction. Sikanderpur, MG Road, HUDA City Centre, and IFFCO Chowk corridors offer the widest apartment choice with walkable or short-drive station access.'],
            'apartment_smart' => ['keyword' => 'smart homes in Gurgaon', 'intro' => 'Smart homes integrate app-controlled lighting, climate, access, and security — increasingly standard in new Gurgaon launches and premium tower retrofits on Golf Course Extension.'],
            'apartment_pets' => ['keyword' => 'pet-friendly apartments in Gurgaon', 'intro' => 'Pet-friendly societies explicitly allow dogs and cats with documented policies — critical for tenants and owners. Policies vary widely; always confirm breed/size rules and park access before signing.'],
            'apartment_family' => ['keyword' => 'family apartments in Gurgaon', 'intro' => 'Family apartments prioritise school proximity, playground access, low internal traffic, and 3–4 BHK layouts with reliable power and water backup across established sectors.'],
            'apartment_premium' => ['keyword' => 'premium apartments in Gurgaon', 'intro' => 'Premium apartments deliver branded specifications, concierge-grade services, and address prestige — primarily on Golf Course Road, DLF premium blocks, and top Extension towers.'],
            'builder_overview' => ['keyword' => 'top builders in Gurgaon', 'intro' => 'Gurgaon\'s builder landscape spans legacy names like DLF and Bestech to national brands Godrej, Sobha, and Emaar plus active New Gurgaon developers. Delivery track record matters more than brochure renderings.'],
            'builder_dlf' => ['keyword' => 'DLF projects in Gurgaon', 'intro' => 'DLF is synonymous with Gurgaon residential branding — from DLF City phases to Alameda, Gardencity, and premium towers. Buyers pay for liquidity, rental depth, and mature civic fabric.'],
            'builder_m3m' => ['keyword' => 'M3M projects in Gurgaon', 'intro' => 'M3M has built a strong luxury and upper-mid presence on Golf Course Extension and Dwarka Expressway with distinctive towers and amenity-heavy marketing — verify delivery on specific phases.'],
            'builder_signature' => ['keyword' => 'Signature Global projects in Gurgaon', 'intro' => 'Signature Global targets value and mid-segment buyers across New Gurgaon and SPR with high-volume townships — compare possession timelines and build quality across phases.'],
            'builder_smartworld' => ['keyword' => 'Smart World projects in Gurgaon', 'intro' => 'Smart World develops large-format townships in New Gurgaon belts with competitive pricing and payment plans — due diligence on delivery history is essential phase by phase.'],
            'builder_godrej' => ['keyword' => 'Godrej Properties in Gurgaon', 'intro' => 'Godrej brings national brand trust, specification discipline, and amenity planning to select Gurgaon corridors — popular with end-users seeking developer credibility.'],
            'builder_sobha' => ['keyword' => 'Sobha projects in Gurgaon', 'intro' => 'Sobha is known for construction quality and detailed finishing standards — appealing to buyers who prioritise build integrity over lowest per-sq-ft headline price.'],
            'builder_adani' => ['keyword' => 'Adani Realty in Gurgaon', 'intro' => 'Adani Realty enters Gurgaon with township-scale ambition and infrastructure-linked marketing — buyers should evaluate phase-wise delivery and resale comps in delivered inventory.'],
            'builder_bestech' => ['keyword' => 'Bestech projects in Gurgaon', 'intro' => 'Bestech townships such as Park View and adjoining belts have long anchored mid-premium Gurgaon living with steady rental and end-user demand.'],
            'builder_emaar' => ['keyword' => 'Emaar projects in Gurgaon', 'intro' => 'Emaar applies international luxury development norms to Gurgaon premium projects — club sizing, specification sheets, and CAM structures deserve close comparison.'],
            'rental_areas' => ['keyword' => 'best areas for rent in Gurgaon', 'intro' => 'Rental demand in Gurgaon tracks employment hubs. Premium rents cluster near Golf Course Road and Cyber City; value rents sit in Old Gurgaon sectors and southern townships with longer commutes.'],
            'rental_furnished' => ['keyword' => 'furnished flats for rent in Gurgaon', 'intro' => 'Furnished leases command premiums from corporate and expatriate tenants. Inventory ranges from fully serviced luxury units to basic furnished 2 BHK for IT professionals.'],
            'rental_trends' => ['keyword' => 'rental market trends in Gurgaon', 'intro' => 'Gurgaon\'s 2026 rental market reflects hybrid work patterns, corporate hiring, and supply in New Gurgaon townships — furnished and pet-friendly units let faster in premium belts.'],
            'rental_pg' => ['keyword' => 'PG vs apartment in Gurgaon', 'intro' => 'Paying guest accommodation offers lower entry cost and included meals; apartments deliver privacy, family suitability, and pet flexibility. The right choice depends on tenure, budget, and lifestyle.'],
            'rental_yield' => ['keyword' => 'rental yield comparison in Gurgaon', 'intro' => 'Yield varies more by buy-in price and society quality than by city averages. Mid sectors often outperform ultra-luxury on percentage yield; premium addresses win on vacancy and tenant quality.'],
            'rental_professionals' => ['keyword' => 'best areas for working professionals in Gurgaon', 'intro' => 'Working professionals optimise for commute time, furnished availability, and social infrastructure — DLF phases, Sushant Lok, and Cyber-adjacent sectors dominate shortlists.'],
            'rental_cyber' => ['keyword' => 'rent near Cyber City Gurgaon', 'intro' => 'Cyber City and Cyber Hub tenants prioritise 15–25 minute peak-hour drives. DLF Phase 2–4, Sushant Lok, and parts of Sector 56 offer the strongest rental depth nearby.'],
            'rental_udyog' => ['keyword' => 'rent near Udyog Vihar Gurgaon', 'intro' => 'Udyog Vihar workforce rentals favour practical 1–3 BHK near industrial phases — functional societies with stable maintenance over luxury amenities.'],
            'rental_golf' => ['keyword' => 'rent near Golf Course Road Gurgaon', 'intro' => 'Golf Course Road rentals serve senior executives and expatriates expecting furnished 3–4 BHK, club access, and premium security — leases are often corporate-backed.'],
            'rental_luxury' => ['keyword' => 'luxury rental apartments in Gurgaon', 'intro' => 'Luxury rentals combine branded towers, designer furnishing, and full-service lease structures — concentrated on Golf Course Road, select DLF inventory, and top Extension projects.'],
            default => ['keyword' => 'Gurgaon apartments', 'intro' => 'Gurgaon offers one of NCR\'s deepest apartment markets across budgets and corridors.'],
        };

        $base['sections'] = self::sectionsFor($category, $slug);

        return $base;
    }

    /**
     * @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>}
     */
    private static function sectionsFor(string $category, string $slug): array
    {
        $isBuilder = str_starts_with($category, 'builder_');
        $isRental = str_starts_with($category, 'rental_');

        if ($isBuilder) {
            return self::builderSections($category, $slug);
        }

        if ($isRental) {
            return self::rentalSections($category, $slug);
        }

        return self::apartmentSections($category, $slug);
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function apartmentSections(string $category, string $slug): array
    {
        $specific = match ($category) {
            'apartment_2bhk' => ['heading' => 'Best Areas for 2 BHK Flats', 'paragraphs' => ['Mid sectors 56–57, Palam Vihar, and New Gurgaon townships offer value 2 BHK stock.', 'Premium 2 BHK+study units cluster near Golf Course Extension and DLF phases for corporate tenants.'], 'list' => ['Sectors 56, 57 — family and rental depth', 'DLF Phase 3–5 — premium end-user', 'Dwarka Expressway — new towers, competitive pricing', 'MG Road corridor — studio-adjacent 2 BHK rentals']],
            'apartment_3bhk' => ['heading' => 'Top Societies for 3 BHK Buyers', 'paragraphs' => ['Compare carpet area, balcony usability, and parking before per-sq-ft price.', 'South City, Sushant Lok, and DLF societies remain perennial family favourites.'], 'list' => ['South City 1 & 2 — township living', 'Sushant Lok — central connectivity', 'Sector 49–50 — mid Gurgaon value', 'New Gurgaon — larger 3 BHK layouts']],
            'apartment_4bhk' => ['heading' => 'Where to Find 4 BHK Luxury Inventory', 'paragraphs' => ['Golf Course Road towers and DLF premium blocks dominate large-format luxury supply.', 'Verify servant quarter, terrace rights, and parking count on every shortlist.']],
            'apartment_studio' => ['heading' => 'Studio Hotspots', 'paragraphs' => ['MG Road and Sikanderpur-linked societies suit short corporate tenures.', 'Ownership studios are limited — most inventory is rental-focused.']],
            'apartment_gated' => ['heading' => 'Notable Gated Communities', 'paragraphs' => ['Townships differ on club quality, visitor policies, and maintenance escalation history.', 'Visit at evening hours to assess security and internal traffic.'], 'list' => ['South City, Nirvana Country', 'DLF Alameda, Gardencity', 'Rosewood, Sun City, Vatika townships', 'Select Dwarka Expressway projects']],
            'apartment_metro' => ['heading' => 'Metro-Linked Micro-Markets', 'paragraphs' => ['Yellow Line stations drive rental premiums within 1 km walk or short auto ride.', 'Factor last-mile time — not all "near metro" listings are equally connected.'], 'list' => ['Sikanderpur / Guru Dronacharya belt', 'MG Road societies', 'HUDA City Centre adjacency', 'IFFCO Chowk corridor pockets']],
            'apartment_smart' => ['heading' => 'Smart Home Features to Evaluate', 'paragraphs' => ['Ask what is standard vs optional package — automation upsells vary by tower.', 'Check service support for locks, cameras, and HVAC integrations after possession.'], 'list' => ['App-based access and visitor management', 'Smart lighting and climate presets', 'Integrated security cameras', 'Developer vs third-party platform support']],
            'apartment_pets' => ['heading' => 'Finding Pet-Friendly Societies', 'paragraphs' => ['Get pet policy in writing — verbal assurances fail at registration.', 'Prefer societies near parks and with ground-floor access for daily walks.']],
            'apartment_family' => ['heading' => 'Family Apartment Checklist', 'paragraphs' => ['School bus routes, paediatric care proximity, and playground safety matter daily.', '3 BHK minimum is typical; 4 BHK for joint families with staff accommodation.'], 'list' => ['Power and water backup standards', 'Society speed-breakers and internal road safety', 'Visitor and domestic help policies', 'Club and sports facilities for children']],
            'apartment_premium' => ['heading' => 'Premium Apartment Benchmarks', 'paragraphs' => ['CAM, lift count, and parking ratio separate true premium from marketing labels.', 'Compare three towers on the same road — pricing spreads can exceed 20%.']],
            default => ['heading' => 'Apartment Search Tips', 'paragraphs' => ['Define carpet area minimum and commute anchor before browsing portals.', 'Use registration comps — not asking rates alone.']],
        };

        return [
            $specific,
            [
                'heading' => 'Pricing and What Affects Value',
                'paragraphs' => ['Per-sq-ft quotes on super built-up can mislead — insist on carpet area comparisons.', 'Society transfer charges, parking premiums, and floor facing shift effective price materially.'],
            ],
            [
                'heading' => 'Buy vs Rent Decision',
                'paragraphs' => ['High rents in your target society may justify purchase over 5+ year horizon.', 'Short transfers or trial city moves favour furnished rent before buy commitment.'],
            ],
            [
                'heading' => 'Due Diligence Before Token',
                'paragraphs' => ['Review RERA status for under-construction; society NOC path for resale.', 'Inspect at different times — morning traffic, evening noise, weekend parking pressure.'],
                'list' => ['Title and encumbrance basics', 'Maintenance payment history', 'Resident feedback on water pressure', 'Actual vs promised amenities'],
            ],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function builderSections(string $category, string $slug): array
    {
        $name = match ($category) {
            'builder_dlf' => 'DLF',
            'builder_m3m' => 'M3M',
            'builder_signature' => 'Signature Global',
            'builder_smartworld' => 'Smart World',
            'builder_godrej' => 'Godrej Properties',
            'builder_sobha' => 'Sobha',
            'builder_adani' => 'Adani Realty',
            'builder_bestech' => 'Bestech',
            'builder_emaar' => 'Emaar',
            default => 'leading Gurgaon developers',
        };

        $focus = $category === 'builder_overview'
            ? ['heading' => 'How to Compare Top Builders', 'paragraphs' => ['Rank developers on on-time delivery in Gurgaon, not national ad spend.', 'Speak to residents in completed phases about maintenance responsiveness and build defects.'], 'list' => ['DLF — legacy liquidity and rental depth', 'M3M — luxury and upper-mid towers', 'Godrej / Sobha / Emaar — national brand specs', 'Signature Global / Smart World — New Gurgaon volume']]
            : ['heading' => "About {$name} in Gurgaon", 'paragraphs' => ["{$name} projects attract buyers seeking specific brand attributes — compare delivered phases you can inspect today.", 'Review RERA quarterly reports, hidden charges (PLC, parking, club), and resale comps in occupied towers.'], 'list' => ['Completed phase walkthroughs', 'Developer delivery history in NCR', 'Resale liquidity in delivered inventory', 'CAM and club charge trajectory']];

        return [
            $focus,
            [
                'heading' => 'Project Selection Framework',
                'paragraphs' => ['Shortlist two projects from the same builder — quality varies by phase and contractor.', 'Ready resale may beat new launch payment plan if possession is uncertain.'],
            ],
            [
                'heading' => 'Investor vs End-User Fit',
                'paragraphs' => ['End-users prioritise layout, school access, and build quality today.', 'Investors model net rent, vacancy, and exit liquidity in delivered phases only.'],
            ],
        ];
    }

    /** @return list<array{heading: string, paragraphs: list<string>, list?: list<string>}>} */
    private static function rentalSections(string $category, string $slug): array
    {
        $specific = match ($category) {
            'rental_areas' => ['heading' => 'Top Rental Localities by Budget', 'paragraphs' => ['Premium: DLF phases, Golf Course Road adjacency, Sushant Lok.', 'Mid: Sectors 56–57, 43–50. Value: Palam Vihar, Old Gurgaon, southern townships.']],
            'rental_furnished' => ['heading' => 'Furnished Lease Types', 'paragraphs' => ['Corporate leases often include maintenance, Wi-Fi, and housekeeping — clarify in agreement.', 'Semi-furnished saves cost but requires setup time for relocating executives.']],
            'rental_trends' => ['heading' => '2026 Rental Drivers', 'paragraphs' => ['Hybrid work reduced daily CBD pressure but premium furnished demand remains for senior hires.', 'New Gurgaon supply increases 2–3 BHK rental stock with longer commutes to Cyber City.']],
            'rental_pg' => ['heading' => 'Cost and Lifestyle Comparison', 'paragraphs' => ['PG suits short stays under 12 months; apartments win for couples, pets, and WFH setups.', 'All-in PG pricing may exceed EMI on affordable 1 BHK over 3+ years — run the math.'], 'list' => ['PG: lower upfront, shared facilities', 'Apartment: privacy, family-friendly', 'PG: flexible exit', 'Apartment: wealth building if buying']],
            'rental_yield' => ['heading' => 'Yield by Corridor', 'paragraphs' => ['Model net yield after maintenance, furnishing depreciation, and 1 month vacancy annually.', 'Ultra-luxury often shows 2–3% gross; mid sectors can reach 3.5–4.5% on right buy-in.']],
            'rental_professionals' => ['heading' => 'Professional Hotspots', 'paragraphs' => ['Cyber workers favour DLF and Sushant Lok; Golf Course offices pull premium rentals nearby.', 'Test peak commute from society gate — not map centre distance.']],
            'rental_cyber' => ['heading' => 'Societies Near Cyber City', 'paragraphs' => ['DLF Phase 2–4 and Sushant Lok 1–2 dominate corporate tenant demand.', 'Furnished 2–3 BHK with parking let fastest — verify society guest and pet rules.']],
            'rental_udyog' => ['heading' => 'Udyog Vihar Adjacent Rentals', 'paragraphs' => ['Practical 1–2 BHK in nearby sectors suits workforce rotations and project staff.', 'Prioritise stable water/power over club amenities for this tenant profile.']],
            'rental_golf' => ['heading' => 'Golf Course Road Rental Profile', 'paragraphs' => ['Expect corporate-backed leases, embassy-style security asks, and designer furnishing.', 'Landlords should budget higher CAM and furnishing refresh cycles.']],
            'rental_luxury' => ['heading' => 'Luxury Rental Standards', 'paragraphs' => ['Luxury tenants expect concierge responsiveness, backup power, and premium appliance brands.', 'Professional property management improves retention and reduces vacancy gaps.']],
            default => ['heading' => 'Rental Search Tips', 'paragraphs' => ['Confirm deposit, lock-in, escalation clause, and maintenance inclusion before token.', 'Verify owner authority and society NOC for tenant registration.']],
        };

        return [
            $specific,
            [
                'heading' => 'Lease Documentation Checklist',
                'paragraphs' => ['Standard 11-month renewable agreements are common; corporate may require tripartite structures.', 'Inventory list with photos protects both parties on furnished units.'],
                'list' => ['Security deposit and refund terms', 'Maintenance and utility responsibility split', 'Lock-in and notice period', 'Society move-in NOC timeline'],
            ],
            [
                'heading' => 'How Ocean Realtors Helps Tenants and Landlords',
                'paragraphs' => ['We match verified inventory to budget and commute — reducing wasted visits.', 'Landlords get pricing aligned to recent leases in the same society, not stale listings.'],
            ],
        ];
    }

    /**
     * @param  list<array{heading: string, paragraphs: list<string>, list?: list<string>}>  $sections
     */
    private static function article(string $keyword, string $intro, array $sections): string
    {
        $html = '<p>'.$intro.'</p>';
        $html .= '<p>This guide covers <strong>'.$keyword.'</strong> with practical advice for buyers, tenants, and investors in Gurgaon\'s 2026 market. Ocean Realtors shares verified shortlists and registration-backed pricing — not inflated portal quotes.</p>';

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
        $html .= '<p>Whether you are buying, selling, or leasing '.$keyword.', Ocean Realtors provides curated options, society comparisons, site visit coordination, and paperwork guidance. Call or WhatsApp our Gurgaon team for a personalised shortlist today.</p>';

        return $html;
    }
}
