<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            ['name' => 'Ocean Realtors Team', 'city' => 'Delhi', 'designation' => 'Senior Property Consultant', 'phone' => '+911124000001', 'rating' => 4.8, 'reviews_count' => 127, 'experience_years' => 12, 'slug' => 'ocean-realtors-team'],
            ['name' => 'Priya Sharma', 'city' => 'Delhi', 'designation' => 'Residential Sales Expert', 'phone' => '+919810000001', 'rating' => 4.9, 'reviews_count' => 89, 'experience_years' => 8],
            ['name' => 'Rahul Verma', 'city' => 'Gurgaon', 'designation' => 'Luxury Homes Specialist', 'phone' => '+919810000002', 'rating' => 4.8, 'reviews_count' => 112, 'experience_years' => 10],
            ['name' => 'Anita Kapoor', 'city' => 'Mumbai', 'designation' => 'Commercial Leasing Manager', 'phone' => '+919810000003', 'rating' => 4.7, 'reviews_count' => 64, 'experience_years' => 7],
            ['name' => 'Vikram Singh', 'city' => 'Noida', 'designation' => 'Rental Consultant', 'phone' => '+919810000004', 'rating' => 4.8, 'reviews_count' => 95, 'experience_years' => 6],
            ['name' => 'Sneha Reddy', 'city' => 'Bangalore', 'designation' => 'Apartment Specialist', 'phone' => '+919810000005', 'rating' => 4.9, 'reviews_count' => 78, 'experience_years' => 5],
            ['name' => 'Arjun Mehta', 'city' => 'Gurgaon', 'designation' => 'Investment Advisor', 'phone' => '+919810000006', 'rating' => 4.8, 'reviews_count' => 103, 'experience_years' => 9],
            ['name' => 'Kavita Nair', 'city' => 'Mumbai', 'designation' => 'Sea-Facing Properties Expert', 'phone' => '+919810000007', 'rating' => 4.7, 'reviews_count' => 56, 'experience_years' => 11],
            ['name' => 'Mohit Agarwal', 'city' => 'Delhi', 'designation' => 'Plots & Land Specialist', 'phone' => '+919810000008', 'rating' => 4.6, 'reviews_count' => 42, 'experience_years' => 4],
            ['name' => 'Deepika Joshi', 'city' => 'Noida', 'designation' => 'Family Homes Consultant', 'phone' => '+919810000009', 'rating' => 4.9, 'reviews_count' => 71, 'experience_years' => 6],
            ['name' => 'Sanjay Malhotra', 'city' => 'Gurgaon', 'designation' => 'Corporate Housing Lead', 'phone' => '+919810000010', 'rating' => 4.8, 'reviews_count' => 88, 'experience_years' => 13],
            ['name' => 'Meera Iyer', 'city' => 'Bangalore', 'designation' => 'Villa & Row House Expert', 'phone' => '+919810000011', 'rating' => 4.7, 'reviews_count' => 49, 'experience_years' => 5],
            ['name' => 'Karan Bhatia', 'city' => 'Delhi', 'designation' => 'South Delhi Specialist', 'phone' => '+919810000012', 'rating' => 4.8, 'reviews_count' => 134, 'experience_years' => 14],
            ['name' => 'Pooja Gill', 'city' => 'Gurgaon', 'designation' => 'New Launch Specialist', 'phone' => '+919810000013', 'rating' => 4.9, 'reviews_count' => 67, 'experience_years' => 3],
            ['name' => 'Rohit Desai', 'city' => 'Mumbai', 'designation' => 'Western Suburbs Expert', 'phone' => '+919810000014', 'rating' => 4.6, 'reviews_count' => 38, 'experience_years' => 4],
            ['name' => 'Neha Chawla', 'city' => 'Noida', 'designation' => 'PG & Co-living Advisor', 'phone' => '+919810000015', 'rating' => 4.7, 'reviews_count' => 52, 'experience_years' => 2],
            ['name' => 'Amit Khanna', 'city' => 'Delhi', 'designation' => 'Commercial Sales Head', 'phone' => '+919810000016', 'rating' => 4.8, 'reviews_count' => 91, 'experience_years' => 15],
            ['name' => 'Ishita Banerjee', 'city' => 'Kolkata', 'designation' => 'Heritage & Premium Homes', 'phone' => '+919810000017', 'rating' => 4.7, 'reviews_count' => 44, 'experience_years' => 7],
            ['name' => 'Harish Patel', 'city' => 'Ahmedabad', 'designation' => 'Gated Community Expert', 'phone' => '+919810000018', 'rating' => 4.8, 'reviews_count' => 59, 'experience_years' => 8],
            ['name' => 'Simran Kaur', 'city' => 'Chandigarh', 'designation' => 'Farmhouse & Villa Consultant', 'phone' => '+919810000019', 'rating' => 4.9, 'reviews_count' => 73, 'experience_years' => 6],
            ['name' => 'Nikhil Rao', 'city' => 'Hyderabad', 'designation' => 'Tech Corridor Properties', 'phone' => '+919810000020', 'rating' => 4.8, 'reviews_count' => 61, 'experience_years' => 5],
        ];

        foreach ($agents as $data) {
            $explicitSlug = $data['slug'] ?? null;
            unset($data['slug']);

            $existing = Agent::where('name', $data['name'])->first();
            $slug = $explicitSlug ?? $existing?->slug ?? Agent::uniqueSlug($data['name']);

            Agent::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, [
                    'slug' => $slug,
                    'whatsapp_phone' => $data['phone'],
                    'email' => strtolower(str_replace(' ', '.', $data['name'])).'@oceanrealtors.co.in',
                    'bio' => 'Dedicated property consultant helping clients find the right home or investment across '.$data['city'].'.',
                    'languages' => ['English', 'Hindi'],
                    'is_active' => true,
                ])
            );
        }
    }
}
