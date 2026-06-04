<?php

namespace App\Support;

class SeoLandingPageFactory
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        $pages = [];

        foreach (config('seo_landing_areas', []) as $definition) {
            $slug = '3-bhk-builder-floor-for-sale-in-'.$definition['slug_key'];
            $pages[$slug] = self::build($definition);
        }

        return $pages;
    }

    /**
     * @param  array<string, mixed>  $definition
     * @return array<string, mixed>
     */
    public static function build(array $definition): array
    {
        $area = $definition['area'];
        $title = "3 BHK Builder Floor For Sale in {$area}";

        return [
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => "Buy 3 BHK builder floors for sale in {$area} with Ocean Realtors — off-market deals, up to 20% below market price, verified papers, and a dedicated agent. Enquire today.",
            'area' => $area,
            'hero_badge' => "{$area} · Builder floors · For sale",
            'hero_subtitle' => $definition['hero_subtitle'],
            'hero_image' => $definition['hero_image'],
            'intent' => 'buy',
            'city' => 'Gurgaon',
            'why_intro' => $definition['why_intro'],
            'guide_lead' => $definition['guide_lead'],
            'guide_body' => $definition['guide_body'],
            'location_detail' => $definition['location_detail'],
            'features' => self::features($area),
            'highlights' => $definition['highlights'],
            'faqs' => self::faqs($area),
        ];
    }

    /**
     * @return list<array{title: string, description: string, icon: string}>
     */
    private static function features(string $area): array
    {
        return [
            ['title' => 'Off-market deals', 'description' => "See builder floors in {$area} before they hit public portals — sourced through owner networks and local intelligence.", 'icon' => 'spark'],
            ['title' => '20% less than market price', 'description' => 'Our research team benchmarks every option against recent transactions so you pay fair value, not inflated asking rates.', 'icon' => 'tag'],
            ['title' => 'Dedicated agent support', 'description' => 'One named Ocean Realtors consultant from shortlist to registration — calls, WhatsApp, and coordinated site visits.', 'icon' => 'agent'],
            ['title' => 'Multiple options', 'description' => 'Compare 3 BHK layouts across blocks, facing, terrace size, and renovation status — not a single forced choice.', 'icon' => 'grid'],
            ['title' => 'Special R&D team', 'description' => "A dedicated research desk tracks {$area} pricing, society norms, and new supply so your shortlist stays current.", 'icon' => 'chart'],
            ['title' => 'Property papers check', 'description' => 'Title, society NOC, transfer charges, and encumbrance basics reviewed before you commit a token.', 'icon' => 'shield'],
        ];
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    private static function faqs(string $area): array
    {
        return [
            ['question' => "What is the typical price range for a 3 BHK builder floor in {$area}?", 'answer' => 'Prices depend on block, facing, floor plate, parking, and renovation. Ocean Realtors shares recent transaction references and off-market options so you can compare value — not just asking rates.'],
            ['question' => 'How does Ocean Realtors verify property papers?', 'answer' => 'We review title chain basics, society transfer requirements, and outstanding dues before recommending a property. For final registration, your lawyer should still complete full legal due diligence.'],
            ['question' => 'Can I see homes that are not listed online?', 'answer' => "Yes. A significant share of our {$area} inventory comes through off-market and owner-direct channels that never appear on mass listing sites."],
            ['question' => 'How quickly will an Ocean Realtors agent contact me?', 'answer' => 'After you submit the enquiry form, a dedicated consultant typically calls or WhatsApps you within two business hours during office hours (Mon–Sat, 10 AM–7 PM).'],
            ['question' => 'Do you help with home loans and registration?', 'answer' => 'We coordinate site visits, negotiation, and paperwork guidance. Bank tie-ups and registration are handled with your chosen lender and advocate; we stay involved for a smooth close.'],
        ];
    }
}
