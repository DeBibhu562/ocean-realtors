<?php

namespace App\Support;

class SeoLandingFourBhkPageFactory
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        $pages = [];

        foreach (self::areaDefinitions() as $definition) {
            $slug = '4-bhk-builder-floors-for-sale-in-'.$definition['slug_key'];
            $pages[$slug] = self::build($definition);
        }

        return $pages;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function areaDefinitions(): array
    {
        $definitions = config('seo_landing_4bhk', []);
        $existingSlugs = array_flip(array_column($definitions, 'slug_key'));

        foreach (SeoLandingFourBhkAreaExpander::expandAll(config('seo_landing_4bhk_bulk_list', [])) as $definition) {
            if (isset($existingSlugs[$definition['slug_key']])) {
                continue;
            }

            $definitions[] = $definition;
            $existingSlugs[$definition['slug_key']] = true;
        }

        return $definitions;
    }

    /**
     * @param  array<string, mixed>  $definition
     * @return array<string, mixed>
     */
    public static function build(array $definition): array
    {
        $area = $definition['area'];
        $title = "4 BHK Builder Floors for Sale in {$area}";

        return [
            'listing_type' => '4bhk_builder_floor',
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => "Buy 4 BHK builder floors for sale in {$area} with Ocean Realtors — off-market spacious homes, up to 20% below market price, verified papers, and a dedicated agent. Enquire for {$area} floors today.",
            'area' => $area,
            'hero_badge' => "{$area} · 4 BHK builder floors · For sale",
            'hero_subtitle' => $definition['hero_subtitle'],
            'hero_image' => $definition['hero_image'],
            'intent' => 'buy',
            'city' => 'Gurgaon',
            'why_intro' => $definition['why_intro'],
            'why_heading' => $definition['why_heading'] ?? "Why buy a 4 BHK builder floor in {$area} with Ocean Realtors?",
            'guide_lead' => $definition['guide_lead'],
            'guide_body' => $definition['guide_body'],
            'guide_heading' => $definition['guide_heading'] ?? "4 BHK builder floors for sale in {$area} — what spacious-home buyers should know",
            'guide_highlight' => $definition['guide_highlight'] ?? "A 4 BHK builder floor for sale in {$area} delivers room for extended families, home offices, and terrace living — with independent low-rise privacy that high-rise stock rarely matches.",
            'location_detail' => $definition['location_detail'],
            'features' => self::features($area),
            'highlights' => $definition['highlights'],
            'faqs' => self::faqs($area),
            'process_steps' => $definition['process_steps'] ?? null,
            'cta' => [
                'hero_primary' => 'Get 4 BHK shortlist from Ocean Realtors',
                'whatsapp' => "Hi Ocean Realtors, I am looking for 4 BHK builder floors for sale in {$area}. Please share options.",
                'mid_subline' => "Tell us your budget, bedroom layout, and possession timeline — we match you with verified 4 BHK builder floors in {$area}, including off-market homes.",
                'banner_whatsapp' => "Hi Ocean Realtors, I want to buy a 4 BHK builder floor in {$area}. Please call me.",
                'banner_subline' => '20% smarter pricing · Off-market spacious floors · Dedicated agent · Papers checked before token.',
                'footer_headline' => "Still comparing 4 BHK floors in {$area}? Let Ocean Realtors narrow it down.",
            ],
        ];
    }

    /**
     * @return list<array{title: string, description: string, icon: string}>
     */
    private static function features(string $area): array
    {
        return [
            [
                'title' => 'Off-market deals',
                'description' => "Access spacious 4 BHK builder floors in {$area} before they appear on public portals — sourced through owner networks and local transaction intelligence.",
                'icon' => 'spark',
            ],
            [
                'title' => '20% less than market price',
                'description' => 'Our research team benchmarks every 4 BHK option against recent registrations so you avoid inflated broker quotes and stale portal asking rates.',
                'icon' => 'tag',
            ],
            [
                'title' => 'Dedicated agent support',
                'description' => 'One named Ocean Realtors consultant from shortlist to registration — calls, WhatsApp, and coordinated site visits for large-format homes.',
                'icon' => 'agent',
            ],
            [
                'title' => 'Multiple options',
                'description' => "Compare 4 BHK layouts across blocks — bedroom spread, servant quarter, terrace size, parking bays, and renovation status — not a single forced choice.",
                'icon' => 'grid',
            ],
            [
                'title' => 'Special R&D team',
                'description' => "A dedicated research desk tracks {$area} 4 BHK pricing, society norms, and new supply so your spacious-home shortlist stays current.",
                'icon' => 'chart',
            ],
            [
                'title' => 'Property papers check',
                'description' => 'Title chain, society NOC, transfer charges, and encumbrance basics reviewed before you commit token on a 4 BHK builder floor.',
                'icon' => 'shield',
            ],
        ];
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    private static function faqs(string $area): array
    {
        return [
            [
                'question' => "What is the typical price range for a 4 BHK builder floor in {$area}?",
                'answer' => 'Prices depend on built-up area, floor plate, terrace, servant quarter, parking, and renovation quality. Ocean Realtors shares recent transaction references and off-market 4 BHK options so you compare value per sq ft — not just headline asking rates.',
            ],
            [
                'question' => 'How does Ocean Realtors verify property papers for 4 BHK floors?',
                'answer' => 'We review title chain basics, society transfer requirements, outstanding dues, and encumbrance flags before recommending a home. Your advocate should still complete full legal due diligence before registration.',
            ],
            [
                'question' => 'Can I see 4 BHK homes that are not listed online?',
                'answer' => "Yes. Many {$area} owners with large builder floors prefer discreet sales. A significant share of our 4 BHK inventory is off-market and owner-direct.",
            ],
            [
                'question' => 'Do 4 BHK builder floors include servant quarters and terrace access?',
                'answer' => 'Most premium 4 BHK plates in Gurgaon include a servant room or quarter and terrace or roof rights — but layouts vary by plot and construction year. Ocean Realtors notes these details on every shortlist.',
            ],
            [
                'question' => 'How quickly will an Ocean Realtors agent contact me?',
                'answer' => 'After you submit the enquiry form, a dedicated consultant typically calls or WhatsApps you within two business hours during office hours (Mon–Sat, 10 AM–7 PM).',
            ],
        ];
    }
}
