<?php

namespace App\Support;

class SeoLandingPlotPageFactory
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        $pages = [];

        foreach (self::plotDefinitions() as $definition) {
            $slug = 'plot-for-sale-in-'.$definition['slug_key'];
            $pages[$slug] = self::build($definition);
        }

        return $pages;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function plotDefinitions(): array
    {
        $definitions = config('seo_landing_plots', []);
        $existingSlugs = array_flip(array_column($definitions, 'slug_key'));

        foreach (SeoLandingPlotAreaExpander::expandAll(config('seo_landing_plots_bulk_list', [])) as $definition) {
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
        $title = "Plot for Sale in {$area}";

        return [
            'listing_type' => 'plot',
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => "Buy plots for sale in {$area} with Ocean Realtors — off-market land deals, up to 20% below market price, title verification, and a dedicated agent. Enquire for {$area} parcels today.",
            'area' => $area,
            'hero_badge' => "{$area} · Residential plots · For sale",
            'hero_subtitle' => $definition['hero_subtitle'],
            'hero_image' => $definition['hero_image'],
            'intent' => 'buy',
            'city' => 'Gurgaon',
            'why_intro' => $definition['why_intro'],
            'why_heading' => $definition['why_heading'] ?? "Why buy a plot in {$area} with Ocean Realtors?",
            'guide_lead' => $definition['guide_lead'],
            'guide_body' => $definition['guide_body'],
            'guide_heading' => $definition['guide_heading'] ?? "Plots for sale in {$area} — what land buyers should know",
            'guide_highlight' => $definition['guide_highlight'] ?? "A residential plot for sale in {$area} offers flexibility to design your home or hold land as a long-term Gurgaon asset.",
            'location_detail' => $definition['location_detail'],
            'features' => self::features($area),
            'highlights' => $definition['highlights'],
            'faqs' => self::faqs($area),
            'process_steps' => $definition['process_steps'] ?? null,
            'cta' => [
                'hero_primary' => 'Get plot shortlist from Ocean Realtors',
                'whatsapp' => "Hi Ocean Realtors, I am looking for plots for sale in {$area}. Please share options.",
                'mid_subline' => "Tell us your budget and preferred plot size — we match you with verified land parcels in {$area}, including off-market listings.",
                'banner_whatsapp' => "Hi Ocean Realtors, I want to buy a plot in {$area}. Please call me.",
                'banner_subline' => '20% smarter land pricing · Off-market parcels · Dedicated agent · Title papers checked before token.',
                'footer_headline' => "Still comparing plots in {$area}? Let Ocean Realtors narrow it down.",
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
                'description' => "Access plots in {$area} before they appear on public portals — sourced through landowner networks and local parcel intelligence.",
                'icon' => 'spark',
            ],
            [
                'title' => '20% less than market price',
                'description' => 'Our research team benchmarks price per sq yd against recent registrations so you avoid inflated broker quotes and stale portal rates.',
                'icon' => 'tag',
            ],
            [
                'title' => 'Dedicated agent support',
                'description' => 'One named Ocean Realtors land consultant from shortlist to registration — calls, WhatsApp, and coordinated site inspections.',
                'icon' => 'agent',
            ],
            [
                'title' => 'Multiple options',
                'description' => "Compare plot sizes, corner positions, road frontage, and facing across {$area} — not a single forced parcel.",
                'icon' => 'grid',
            ],
            [
                'title' => 'Special R&D team',
                'description' => "A dedicated research desk tracks {$area} land transactions, authority norms, and new supply so your plot shortlist stays current.",
                'icon' => 'chart',
            ],
            [
                'title' => 'Property papers check',
                'description' => 'Title chain, transfer charges, society or authority NOC basics, and encumbrance screening reviewed before you pay token.',
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
                'question' => "What is the typical price per sq yd for a plot in {$area}?",
                'answer' => 'Land rates depend on plot size, road width, corner premium, facing, and title clarity. Ocean Realtors shares recent registry references and off-market parcels so you compare value — not broker hype.',
            ],
            [
                'question' => 'How does Ocean Realtors verify plot papers?',
                'answer' => 'We review ownership chain, outstanding dues, society or authority transfer requirements, and basic encumbrance flags before recommending a parcel. Your advocate should still complete full legal due diligence before registration.',
            ],
            [
                'question' => 'Can I see plots that are not listed online?',
                'answer' => "Yes. Many {$area} land deals move through private channels. A significant share of our plot inventory is off-market and owner-direct.",
            ],
            [
                'question' => 'Can I build immediately after buying the plot?',
                'answer' => 'Build timing depends on CLU status, society or MC approvals, and transfer completion. Ocean Realtors outlines the typical path for your shortlisted parcel so you plan realistically.',
            ],
            [
                'question' => 'How quickly will an Ocean Realtors agent contact me?',
                'answer' => 'After you submit the enquiry form, a dedicated consultant typically calls or WhatsApps you within two business hours during office hours (Mon–Sat, 10 AM–7 PM).',
            ],
        ];
    }
}
