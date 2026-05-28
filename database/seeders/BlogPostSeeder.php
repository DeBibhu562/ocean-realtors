<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\BlogWriter;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first() ?? User::first();

        if (! $admin) {
            return;
        }

        $writerIds = BlogWriter::query()->orderBy('sort_order')->pluck('id')->all();
        if ($writerIds === []) {
            $this->call(BlogWriterSeeder::class);
            $writerIds = BlogWriter::query()->orderBy('sort_order')->pluck('id')->all();
        }

        $posts = [
            [
                'title' => 'Top 5 Localities to Rent in Gurgaon in 2026',
                'slug' => 'top-localities-rent-gurgaon-2026',
                'excerpt' => 'A practical guide to Gurgaon\'s most sought-after rental neighbourhoods for families and working professionals.',
                'content' => '<h2>Why Gurgaon remains a rental hotspot</h2><p>Gurgaon continues to attract tenants from across NCR thanks to corporate hubs, metro connectivity, and modern housing societies. Whether you are a young professional or a family, choosing the right sector can save commute time and improve quality of life.</p><h3>Sector 56 &amp; 57</h3><p>Popular for mid-range apartments and strong social infrastructure. Good options for 2–3 BHK rentals with schools and markets nearby.</p><h3>DLF Phase 1–5</h3><p>Premium addresses with gated communities, parks, and proximity to Golf Course Road offices.</p><h3>Sohna Road corridor</h3><p>Value-driven new projects with larger floor plans — ideal for buyers transitioning from rent to purchase.</p><p><strong>Tip:</strong> Always verify society maintenance, power backup, and visitor policies before signing a rent agreement.</p>',
                'meta_title' => 'Best Areas to Rent in Gurgaon (2026 Guide)',
                'meta_description' => 'Discover the top Gurgaon localities for renters in 2026 — sectors, budgets, and tips from Ocean Realtors.',
            ],
            [
                'title' => 'First-Time Home Buyer Checklist for NCR',
                'slug' => 'first-time-home-buyer-checklist-ncr',
                'excerpt' => 'Essential steps before you book your first apartment or villa in Delhi NCR — documentation, loans, and site visits.',
                'content' => '<h2>Before you book</h2><p>Buying your first home in NCR is exciting but requires careful planning. Use this checklist to stay organised from shortlist to registration.</p><ul><li>Get loan pre-approval and understand EMI impact</li><li>Verify RERA registration and builder track record</li><li>Compare carpet vs built-up area in the brochure</li><li>Inspect construction quality on site, not just the sample flat</li></ul><h2>At the time of booking</h2><p>Read the sale agreement carefully. Clarify possession timeline, parking allotment, and transfer charges. Keep all receipts and correspondence.</p><p>Ocean Realtors consultants can help you compare projects and coordinate site visits across Gurgaon and Noida.</p>',
                'meta_title' => 'First-Time Home Buyer Checklist | NCR',
                'meta_description' => 'A complete first-time home buyer checklist for Delhi NCR — loans, RERA, site visits, and booking tips.',
            ],
            [
                'title' => 'Commercial Office Space Trends on Golf Course Road',
                'slug' => 'commercial-office-golf-course-road-trends',
                'excerpt' => 'How demand for Grade A offices is shaping rents and fit-out expectations along Golf Course Road.',
                'content' => '<h2>Demand drivers</h2><p>Golf Course Road remains a preferred address for financial services, consulting, and tech firms setting up NCR headquarters. Flexible lease terms and fitted spaces are in high demand.</p><h2>What tenants should evaluate</h2><p>Look beyond per-sq-ft rent — factor in CAM charges, parking ratios, lift capacity, and after-hours access. A slightly higher rent with better infrastructure often reduces operational friction.</p><blockquote>Leasing through a verified consultant helps you negotiate lock-in, rent-free fit-out periods, and exit clauses.</blockquote><p>Contact Ocean Realtors for curated commercial listings with direct owner and developer access.</p>',
                'meta_title' => 'Golf Course Road Office Space Trends',
                'meta_description' => 'Commercial office trends on Gurgaon Golf Course Road — rents, fit-outs, and leasing tips for businesses.',
            ],
            [
                'title' => 'How to Verify a Property Listing Before You Visit',
                'slug' => 'verify-property-listing-before-visit',
                'excerpt' => 'Avoid wasted site visits — learn what to check online and what to confirm with your agent beforehand.',
                'content' => '<h2>Online checks</h2><p>Match photos with Google Maps street view where possible. Confirm the society name, tower, and approximate floor. Ask for a recent video walkthrough if photos look dated.</p><h2>Questions for your agent</h2><ul><li>Is the unit owner-occupied or tenanted?</li><li>What is included in maintenance?</li><li>Are pets allowed?</li><li>Is the price negotiable and what is the expected deposit?</li></ul><p>On Ocean Realtors, every listing is assigned to a named agent you can call or WhatsApp directly for transparent answers.</p>',
                'meta_title' => 'How to Verify Property Listings Online',
                'meta_description' => 'Learn how to verify property listings before site visits — photos, society details, and agent questions.',
            ],
        ];

        foreach ($posts as $index => $data) {
            $content = $data['content'];
            unset($data['content']);

            BlogPost::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'user_id' => $admin->id,
                    'blog_writer_id' => $writerIds[$index % count($writerIds)] ?? null,
                    'content' => $content,
                    'status' => BlogPost::STATUS_PUBLISHED,
                    'published_at' => now()->subDays(rand(2, 20)),
                    'reading_time_minutes' => BlogPost::estimateReadingTime($content),
                ])
            );
        }

        $this->command?->call('blog:attach-featured-images');
    }
}
