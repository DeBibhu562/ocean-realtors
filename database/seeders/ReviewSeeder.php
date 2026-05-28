<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('is_admin', true)->value('id');

        $testimonials = [
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul.sharma@example.com',
                'title' => 'Rented in Sector 56 · Gurgaon',
                'body' => 'Found a 3BHK in two weeks. The agent was transparent about society rules and brokerage — smooth handover.',
                'rating' => 5,
            ],
            [
                'name' => 'Priya Verma',
                'email' => 'priya.verma@example.com',
                'title' => 'Property owner · Gurgaon',
                'body' => 'Listed our apartment on Ocean Realtors and got serious tenants within days. Verification gave buyers confidence.',
                'rating' => 5,
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit.patel@example.com',
                'title' => 'Commercial lease · Gurgaon',
                'body' => 'Helped us shortlist office space on Golf Course Road. Team understood our budget and timeline.',
                'rating' => 5,
            ],
            [
                'name' => 'Neha Kapoor',
                'email' => 'neha.kapoor@example.com',
                'title' => 'First-time buyer · Delhi NCR',
                'body' => 'They explained documentation clearly and coordinated site visits on weekends. Felt supported throughout.',
                'rating' => 5,
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh@example.com',
                'title' => 'Investor · Noida',
                'body' => 'Good filter tools and accurate sq.ft. details. Easier to compare projects before calling the agent.',
                'rating' => 4,
            ],
            [
                'name' => 'Sneha Reddy',
                'email' => 'sneha.reddy@example.com',
                'title' => 'PG search · Gurgaon',
                'body' => 'Found PG near my office in Cyber City. WhatsApp follow-up was quick and professional.',
                'rating' => 5,
            ],
        ];

        foreach ($testimonials as $item) {
            Review::query()->updateOrCreate(
                [
                    'target_type' => Review::TARGET_SITE,
                    'target_id' => null,
                    'email' => $item['email'],
                ],
                [
                    'name' => $item['name'],
                    'title' => $item['title'],
                    'body' => $item['body'],
                    'rating' => $item['rating'],
                    'status' => Review::STATUS_APPROVED,
                    'approved_at' => now(),
                    'approved_by' => $adminId,
                ]
            );
        }
    }
}
